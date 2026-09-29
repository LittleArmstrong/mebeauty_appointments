# API Reference: pdo_mysql_driver.php

**Language**: PHP

**Source**: `system/database/drivers/pdo/subdrivers/pdo_mysql_driver.php`

---

## Classes

### CI_DB_pdo_mysql_driver

**Inherits from**: CI_DB_pdo_driver

#### Methods

##### __construct(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |


##### db_connect(persistent = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| persistent | None | FALSE | - |


##### db_select(database = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| database | None | '' | - |


##### _trans_begin()


##### _trans_commit()


##### _trans_rollback()


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


##### _truncate(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### _from_tables()




## Functions

### __construct(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |

**Returns**: (none)



### db_connect(persistent = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| persistent | None | FALSE | - |

**Returns**: (none)



### db_select(database = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| database | None | '' | - |

**Returns**: (none)



### _trans_begin()

**Returns**: (none)



### _trans_commit()

**Returns**: (none)



### _trans_rollback()

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



### _truncate(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### _from_tables()

**Returns**: (none)


