# API Reference: pdo_oci_driver.php

**Language**: PHP

**Source**: `system/database/drivers/pdo/subdrivers/pdo_oci_driver.php`

---

## Classes

### CI_DB_pdo_oci_driver

**Inherits from**: CI_DB_pdo_driver

#### Methods

##### __construct(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |


##### version()


##### _list_tables(prefix_limit = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| prefix_limit | None | FALSE | - |


##### _list_columns(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |


##### field_data(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### _insert_batch(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |


##### _delete(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### _limit(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |




## Functions

### __construct(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |

**Returns**: (none)



### version()

**Returns**: (none)



### _list_tables(prefix_limit = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| prefix_limit | None | FALSE | - |

**Returns**: (none)



### _list_columns(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |

**Returns**: (none)



### field_data(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### _insert_batch(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |

**Returns**: (none)



### _delete(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### _limit(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |

**Returns**: (none)


