# API Reference: DB_forge.php

**Language**: PHP

**Source**: `system/database/DB_forge.php`

---

## Classes

### CI_DB_forge

**Inherits from**: (none)

#### Methods

##### __construct(&$db)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$db | None | - | - |


##### create_database(db_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db_name | None | - | - |


##### drop_database(db_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db_name | None | - | - |


##### add_key(key, primary = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| primary | None | FALSE | - |


##### add_field(field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |


##### create_table(table, if_not_exists = FALSE, attributes: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| if_not_exists | None | FALSE | - |
| attributes | array | array( | - |


##### _create_table(table, if_not_exists, attributes)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| if_not_exists | None | - | - |
| attributes | None | - | - |


##### _create_table_attr(attributes)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| attributes | None | - | - |


##### drop_table(table_name, if_exists = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |
| if_exists | None | FALSE | - |


##### _drop_table(table, if_exists)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| if_exists | None | - | - |


##### rename_table(table_name, new_table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |
| new_table_name | None | - | - |


##### add_column(table, field, _after = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| field | None | - | - |
| _after | None | NULL | - |


##### drop_column(table, column_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| column_name | None | - | - |


##### modify_column(table, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| field | None | - | - |


##### _alter_table(alter_type, table, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| alter_type | None | - | - |
| table | None | - | - |
| field | None | - | - |


##### _process_fields(create_table = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| create_table | None | FALSE | - |


##### _process_column(field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |


##### _attr_type(&$attributes)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |


##### _attr_unsigned(&$attributes, &$field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |
| &$field | None | - | - |


##### _attr_default(&$attributes, &$field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |
| &$field | None | - | - |


##### _attr_unique(&$attributes, &$field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |
| &$field | None | - | - |


##### _attr_auto_increment(&$attributes, &$field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |
| &$field | None | - | - |


##### _process_primary_keys(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### _process_indexes(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### _reset()




## Functions

### __construct(&$db)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$db | None | - | - |

**Returns**: (none)



### create_database(db_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db_name | None | - | - |

**Returns**: (none)



### drop_database(db_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db_name | None | - | - |

**Returns**: (none)



### add_key(key, primary = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| primary | None | FALSE | - |

**Returns**: (none)



### add_field(field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |

**Returns**: (none)



### create_table(table, if_not_exists = FALSE, attributes: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| if_not_exists | None | FALSE | - |
| attributes | array | array( | - |

**Returns**: (none)



### _create_table(table, if_not_exists, attributes)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| if_not_exists | None | - | - |
| attributes | None | - | - |

**Returns**: (none)



### _create_table_attr(attributes)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| attributes | None | - | - |

**Returns**: (none)



### drop_table(table_name, if_exists = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |
| if_exists | None | FALSE | - |

**Returns**: (none)



### _drop_table(table, if_exists)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| if_exists | None | - | - |

**Returns**: (none)



### rename_table(table_name, new_table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |
| new_table_name | None | - | - |

**Returns**: (none)



### add_column(table, field, _after = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| field | None | - | - |
| _after | None | NULL | - |

**Returns**: (none)



### drop_column(table, column_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| column_name | None | - | - |

**Returns**: (none)



### modify_column(table, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| field | None | - | - |

**Returns**: (none)



### _alter_table(alter_type, table, field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| alter_type | None | - | - |
| table | None | - | - |
| field | None | - | - |

**Returns**: (none)



### _process_fields(create_table = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| create_table | None | FALSE | - |

**Returns**: (none)



### _process_column(field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |

**Returns**: (none)



### _attr_type(&$attributes)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |

**Returns**: (none)



### _attr_unsigned(&$attributes, &$field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |
| &$field | None | - | - |

**Returns**: (none)



### _attr_default(&$attributes, &$field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |
| &$field | None | - | - |

**Returns**: (none)



### _attr_unique(&$attributes, &$field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |
| &$field | None | - | - |

**Returns**: (none)



### _attr_auto_increment(&$attributes, &$field)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$attributes | None | - | - |
| &$field | None | - | - |

**Returns**: (none)



### _process_primary_keys(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### _process_indexes(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### _reset()

**Returns**: (none)


