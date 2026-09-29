# API Reference: pdo_driver.php

**Language**: PHP

**Source**: `system/database/drivers/pdo/pdo_driver.php`

---

## Classes

### CI_DB_pdo_driver

**Inherits from**: CI_DB

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


##### version()


##### _execute(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |


##### _trans_begin()


##### _trans_commit()


##### _trans_rollback()


##### _escape_str(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### affected_rows()


##### insert_id(name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | None | NULL | - |


##### _field_data(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### error()


##### _truncate(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### _close()




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



### version()

**Returns**: (none)



### _execute(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |

**Returns**: (none)



### _trans_begin()

**Returns**: (none)



### _trans_commit()

**Returns**: (none)



### _trans_rollback()

**Returns**: (none)



### _escape_str(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### affected_rows()

**Returns**: (none)



### insert_id(name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | None | NULL | - |

**Returns**: (none)



### _field_data(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### error()

**Returns**: (none)



### _truncate(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### _close()

**Returns**: (none)


