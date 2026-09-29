# API Reference: postgre_driver.php

**Language**: PHP

**Source**: `system/database/drivers/postgre/postgre_driver.php`

---

## Classes

### CI_DB_postgre_driver

**Inherits from**: CI_DB

#### Methods

##### _build_dsn()


##### db_connect(persistent = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| persistent | None | FALSE | - |


##### reconnect()


##### version()


##### _execute(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |


##### _trans_begin()


##### _trans_commit()


##### _trans_rollback()


##### is_write_type(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |


##### _escape_str(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### escape(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### affected_rows()


##### insert_id()


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


##### error()


##### order_by(orderby, direction = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| orderby | None | - | - |
| direction | None | '' | - |
| escape | None | NULL | - |


##### _update(table, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |


##### _update_batch(table, values, index)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |
| index | None | - | - |


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


##### _close()




## Functions

### _build_dsn()

**Returns**: (none)



### db_connect(persistent = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| persistent | None | FALSE | - |

**Returns**: (none)



### reconnect()

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



### is_write_type(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |

**Returns**: (none)



### _escape_str(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### escape(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### affected_rows()

**Returns**: (none)



### insert_id()

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



### error()

**Returns**: (none)



### order_by(orderby, direction = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| orderby | None | - | - |
| direction | None | '' | - |
| escape | None | NULL | - |

**Returns**: (none)



### _update(table, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |

**Returns**: (none)



### _update_batch(table, values, index)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |
| index | None | - | - |

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



### _close()

**Returns**: (none)


