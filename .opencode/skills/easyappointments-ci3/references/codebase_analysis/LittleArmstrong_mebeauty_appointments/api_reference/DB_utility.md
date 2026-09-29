# API Reference: DB_utility.php

**Language**: PHP

**Source**: `system/database/DB_utility.php`

---

## Classes

### CI_DB_utility

**Inherits from**: (none)

#### Methods

##### __construct(&$db)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$db | None | - | - |


##### list_databases()


##### database_exists(database_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| database_name | None | - | - |


##### optimize_table(table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |


##### optimize_database()


##### repair_table(table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |


##### csv_from_result(query: CI_DB_result, delim = ', ', newline = "\n", enclosure = '"')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| query | CI_DB_result | - | - |
| delim | None | ' | - |
| ' | None | - | - |
| newline | None | "\n" | - |
| enclosure | None | '"' | - |


##### xml_from_result(query: CI_DB_result, params = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| query | CI_DB_result | - | - |
| params | None | array( | - |


##### backup(params = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | array( | - |




## Functions

### __construct(&$db)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$db | None | - | - |

**Returns**: (none)



### list_databases()

**Returns**: (none)



### database_exists(database_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| database_name | None | - | - |

**Returns**: (none)



### optimize_table(table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |

**Returns**: (none)



### optimize_database()

**Returns**: (none)



### repair_table(table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |

**Returns**: (none)



### csv_from_result(query: CI_DB_result, delim = ', ', newline = "\n", enclosure = '"')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| query | CI_DB_result | - | - |
| delim | None | ' | - |
| ' | None | - | - |
| newline | None | "\n" | - |
| enclosure | None | '"' | - |

**Returns**: (none)



### xml_from_result(query: CI_DB_result, params = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| query | CI_DB_result | - | - |
| params | None | array( | - |

**Returns**: (none)



### backup(params = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | array( | - |

**Returns**: (none)


