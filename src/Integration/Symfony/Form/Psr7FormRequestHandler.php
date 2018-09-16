<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\Type;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\Util\ServerParams;

class Psr7FormRequestHandler implements RequestHandlerInterface
{
    /**
     * @var ServerParams
     */
    private $serverParams;

    public function __construct(ServerParams $serverParams)
    {
        $this->serverParams = $serverParams;
    }

    public function handleRequest(FormInterface $form, $request = null)
    {
        if (false === $request instanceof ServerRequestInterface) {
            throw new UnexpectedTypeException($request, ServerRequestInterface::class);
        }

        $name = $form->getName();
        $method = $form->getConfig()->getMethod();
        if ($method !== $request->getMethod()) {
            return;
        }

        $request = $request->withUploadedFiles($this->removeNonExistantFiles([], $request->getUploadedFiles()));
        // For request methods that must not have a request body we fetch data
        // from the query string. Otherwise we look for data in the request body.
        if ('GET' === $method || 'HEAD' === $method || 'TRACE' === $method) {
            if ('' === $name) {
                $data = $request->getQueryParams();
            } else {
                // Don't submit GET requests if the form's name does not exist
                // in the request
                if (!isset($request->getQueryParams()[$name])) {
                    return;
                }
                $data = $request->getQueryParams()[$name];
            }
        } else {
            // Mark the form with an error if the uploaded size was too large
            // This is done here and not in FormValidator because $_POST is
            // empty when that error occurs. Hence the form is never submitted.
            if ($this->serverParams->hasPostMaxSizeBeenExceeded()) {
                // Submit the form, but don't clear the default values
                $form->submit(null, false);
                $form->addError(new FormError(
                    call_user_func($form->getConfig()->getOption('upload_max_size_message')),
                    null,
                    ['{{ max }}' => $this->serverParams->getNormalizedIniPostMaxSize()]
                ));
                return;
            }

            if ('' === $name) {
                $params = $request->getParsedBody();
                $files = $request->getUploadedFiles();
            } elseif (isset($request->getParsedBody()[$name]) || isset($request->getUploadedFiles()[$name])) {
                $default = $form->getConfig()->getCompound() ? [] : null;
                $params = $request->getParsedBody()[$name] ?? $default;
                $files = $request->getUploadedFiles()[$name] ?? $default;
            } else {
                // Don't submit the form if it is not present in the request
                return;
            }

            if (is_array($params) && is_array($files)) {
                $data = array_replace_recursive($params, $files);
            } else {
                $data = $params ?: $files;
            }
        }

        // Don't auto-submit the form unless at least one field is present.
        if ('' === $name && count(array_intersect_key($data, $form->all())) <= 0) {
            return;
        }

        $form->submit($data, 'PATCH' !== $method);
    }

    public function isFileUpload($data)
    {
        return $data instanceof UploadedFileInterface;
    }

    /**
     * Empty form field value is mapped to a UploadedFileInterface, which then
     * is transformed to Symfony's UploadedFile, ending in an form violation.
     *
     * @param array $accumulator
     * @param array $files
     * @return array
     */
    private function removeNonExistantFiles(array $accumulator, array $files): array
    {
        foreach ($files as $key => $value) {
            if (true === is_array($value)) {
                $accumulator[$key] = $this->removeNonExistantFiles($accumulator, $value);
            } elseif (true === $value instanceof UploadedFileInterface
                && UPLOAD_ERR_NO_FILE !== $value->getError()
            ) {
                $accumulator[$key] = $value;
            }
        }

        return $accumulator;
    }
}
