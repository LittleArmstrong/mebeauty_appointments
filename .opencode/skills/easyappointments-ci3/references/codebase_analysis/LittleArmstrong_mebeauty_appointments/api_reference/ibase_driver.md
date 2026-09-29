# API Reference: ibase_driver.php

**Language**: PHP

**Source**: `system/database/drivers/ibase/ibase_driver.php`

---

## Classes

### CI_DB_ibase_driver

**Inherits from**: CI_DB

#### Methods

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


##### affected_rows()


##### insert_id(generator_name, inc_by = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| generator_name | None | - | - |
| inc_by | None | 0 | - |


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


##### _update(table, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |


##### _truncate(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


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


##### _insert_batch(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |


##### _close()




## Functions

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



### affected_rows()

**Returns**: (none)



### insert_id(generator_name, inc_by = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| generator_name | None | - | - |
| inc_by | None | 0 | - |

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



### _update(table, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |

**Returns**: (none)



### _truncate(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

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



### _insert_batch(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |

**Returns**: (none)



### _close()

**Returns**: (none)


