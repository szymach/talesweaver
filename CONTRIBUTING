# How to contribute
Currently what this project is looking for is help in regards of the frontend.
A lot could be added to make it more friendly to use and look smoother. There is
a project `TODO` where various task are written down, so feel free to look them up.

## Testing
Backend tests are handled by the [Codeception](https://codeception.com/) framework,
while frontend integration tests are covered with [Cypress](https://www.cypress.io/).
In order to run a full test suite, run the following commands:
```bash
vendor/bin/codecept run
# load necessary test data
bin/console doctrine:fixtures:load -n --group=integration
node_modules/.bin/yarn {run|open}
```
Backend tests should be run using the `test` environment, while frontend with
the `test_cypress` one.

## Submitting changes
You will need to create a pull request to the master branch. Also, the branch from
which you create a PR *needs* to be up to date with the master, to prevent uncaught
bugs getting into it.

## Coding standards

- Max 120 characters per line,
- 4 space tab,
- for PHP code you will need to follow PSR-2 standards,
- yoda operator for comparisons,
- strict checks everywhere.

There are no specific guildelines for the {Java|Type}script code yet.
