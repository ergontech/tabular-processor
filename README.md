# Tabular Data Processor

This is the core of the tabular data processor. It's designed to be used with PHP 5.5 and above.

## Installation

Use [Composer](http://getcomposer.org/). `composer require ergontech/tabular-core`. Currently, there is no [repository](https://getcomposer.org/doc/05-repositories.md) where Tabular can be found, so it must be added as a [VCS repo](https://getcomposer.org/doc/05-repositories.md#vcs).

## Concepts

Tabular is built similarly to a PHP middleware (and was heavily influenced by [Slim's implementation](http://www.slimframework.com/docs/concepts/middleware.html)) in that it achieves an end goal by executing a series of `Steps`, with each Step executing the next one. In this way, one Step can load data from a source, the next step can  transform that data for import, and the final step can save that data to Magento.

## Components

Tabular is built using the following:
* **Rows**: Represents data passed into and out of Steps.
* **Step**: A discrete action, such as _create all nonexistent root categories found in the Rows_ ([example](community/ErgonTech/Tabular/Step/Category/RootCategoryCreator.php))
* **Processor** Container for steps

## Usage

Tabular's "steps" can be run individually as such:

```php
namespace ErgonTech\Tabular;

$sheetsService = \some_fake_sheets_service_getter();
$sheetsLoadStep = new GoogleSheetsLoadStep($sheetsService, 'sheet_id', 'headers', 'data');
$sheetsLoadStep(new Rows([], []), function (Rows $rows) {
    var_dump($rows->getRowsAssoc());
});

```

More commonly, a series of `Step` instances are added to a `Processor`:
```php
namespace ErgonTech\Tabular;

$processor = new Processor;

$processor->addStep(new RowsTransformStep('row_transform_function'));
$processor->addStep(new LoggingStep(\get_a_logger());
$processor->addStep(\get_google_sheets_load_step());

$transformedRows = $processor->run();
```

***IMPORTANT NOTE***: The steps run in <span title="Last In First Out">LIFO</span> order! Don't get bit!

## Contributing

As much as possible, this system is built spec-first. Please include specs when submitting new code.

*When fixing a bug*, please preserve backward-compatibility. *When adding a feature*, please only break backwards-compatibility when the feature necessitates it!

