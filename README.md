# Advanced Workflow Module

[![CI](https://github.com/symbiote/silverstripe-advancedworkflow/actions/workflows/ci.yml/badge.svg)](https://github.com/symbiote/silverstripe-advancedworkflow/actions/workflows/ci.yml)
[![Silverstripe supported module](https://img.shields.io/badge/silverstripe-supported-0071C4.svg)](https://www.silverstripe.org/software/addons/silverstripe-commercially-supported-module-list/)

## Overview

This module is a modification of the [symbiote/silverstripe-advancedworkflow](https://github.com/symbiote/silverstripe-advancedworkflow) customised for DNA Design's specific needs.

A module that provides an action / transition approach to workflow, where a
single workflow process is split into multiple configurable states (Actions)
with multiple possible transitions between the actions.

## Installation
`composer require dnadesign/silverstripe-workflow`
```
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/dnadesign/silverstripe-advancedworkflow.git"
        }
    ]
}
```

The workflow extension is automatically applied to the `SiteTree` class (if available).

## Documentation
 - [User guide](docs/en/userguide/index.md)
 - [Developer documentation](docs/en/index.md)

## Contributing

### Translations

Translations of the natural language strings are managed through a third party translation interface, transifex.com. Newly added strings will be periodically uploaded there for translation, and any new translations will be merged back to the project source code.

Please use [https://www.transifex.com/projects/p/silverstripe-advancedworkflow](https://www.transifex.com/projects/p/silverstripe-advancedworkflow) to contribute translations, rather than sending pull requests with YAML files.
