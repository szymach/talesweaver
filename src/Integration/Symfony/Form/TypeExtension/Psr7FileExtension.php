<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form\TypeExtension;

use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\FileType;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Psr7FileExtension extends AbstractTypeExtension
{
    public function getExtendedType(): string
    {
        return FileType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $transformPsr7FileToSymfonyFile = function (FormEvent $event): void {
            $data = $event->getData();
            if (false === $data instanceof UploadedFileInterface) {
                return;
            }

            $temporaryPath = '';
            $clientFileName = '';
            if (UPLOAD_ERR_NO_FILE !== $data->getError()) {
                $temporaryPath = $this->getTemporaryPath();
                $data->moveTo($temporaryPath);
                $clientFileName = $data->getClientFilename();
            }

            $event->setData(new UploadedFile(
                $temporaryPath,
                $clientFileName ?? '',
                $data->getClientMediaType(),
                $data->getError(),
                true
            ));
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $transformPsr7FileToSymfonyFile, 1);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, $transformPsr7FileToSymfonyFile, 1);
    }

    private function getTemporaryPath(): string
    {
        $temporaryPath = tempnam(sys_get_temp_dir(), uniqid('symfony', true));
        if (false === $temporaryPath) {
            throw new RuntimeException(\sprintf(
                'Unabled to create a temporary directory for path "%s"',
                $temporaryPath
            ));
        }

        return $temporaryPath;
    }
}
