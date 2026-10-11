# API Reference: DB_driver.php

**Language**: PHP

**Source**: `system/database/DB_driver.php`

---

## Classes

### CI_DB_driver

**Inherits from**: (none)

#### Methods

##### __get(name: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |

**Returns**: `mixed`


##### __set(name: string, value: mixed) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |
| value | mixed | - | - |

**Returns**: `void`


##### __construct(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |


##### initialize()


##### db_connect()


##### db_pconnect()


##### reconnect()


##### db_select()


##### error()


##### platform()


##### version()


##### _version()


##### query(sql, binds = FALSE, return_object = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |
| binds | None | FALSE | - |
| return_object | None | NULL | - |


##### simple_query(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |


##### trans_off()


##### trans_strict(mode = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| mode | None | TRUE | - |


##### trans_start(test_mode = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| test_mode | None | FALSE | - |


##### trans_complete()


##### trans_status()


##### trans_active()


##### trans_begin(test_mode = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| test_mode | None | FALSE | - |


##### trans_commit()


##### trans_rollback()


##### compile_binds(sql, binds)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |
| binds | None | - | - |


##### is_write_type(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |


##### elapsed_time(decimals = 6)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| decimals | None | 6 | - |


##### total_queries()


##### last_query()


##### escape(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### escape_str(str, like = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| like | None | FALSE | - |


##### escape_like_str(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### _escape_str(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### primary(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### count_all(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |


##### list_tables(constrain_by_prefix = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| constrain_by_prefix | None | FALSE | - |


##### table_exists(table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |


##### list_fields(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### field_exists(field_name, table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field_name | None | - | - |
| table_name | None | - | - |


##### field_data(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### escape_identifiers(item, split = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| item | None | - | - |
| split | None | TRUE | - |


##### insert_string(table, data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| data | None | - | - |


##### _insert(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |


##### update_string(table, data, where)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| data | None | - | - |
| where | None | - | - |


##### _update(table, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |


##### _has_operator(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### _get_operator(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### call_function(function)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function | None | - | - |


##### cache_set_path(path = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |


##### cache_on()


##### cache_off()


##### cache_delete(segment_one = '', segment_two = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| segment_one | None | '' | - |
| segment_two | None | '' | - |


##### cache_delete_all()


##### _cache_init()


##### close()


##### _close()


##### display_error(error = '', swap = '', native = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| error | None | '' | - |
| swap | None | '' | - |
| native | None | FALSE | - |


##### protect_identifiers(item, prefix_single = FALSE, protect_identifiers = NULL, field_exists = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| item | None | - | - |
| prefix_single | None | FALSE | - |
| protect_identifiers | None | NULL | - |
| field_exists | None | TRUE | - |


##### _reset_select()




## Functions

### __get(name: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |

**Returns**: `mixed`



### __set(name: string, value: mixed) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |
| value | mixed | - | - |

**Returns**: `void`



### __construct(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |

**Returns**: (none)



### initialize()

**Returns**: (none)



### db_connect()

**Returns**: (none)



### db_pconnect()

**Returns**: (none)



### reconnect()

**Returns**: (none)



### db_select()

**Returns**: (none)



### error()

**Returns**: (none)



### platform()

**Returns**: (none)



### version()

**Returns**: (none)



### _version()

**Returns**: (none)



### query(sql, binds = FALSE, return_object = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |
| binds | None | FALSE | - |
| return_object | None | NULL | - |

**Returns**: (none)



### simple_query(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |

**Returns**: (none)



### trans_off()

**Returns**: (none)



### trans_strict(mode = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| mode | None | TRUE | - |

**Returns**: (none)



### trans_start(test_mode = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| test_mode | None | FALSE | - |

**Returns**: (none)



### trans_complete()

**Returns**: (none)



### trans_status()

**Returns**: (none)



### trans_active()

**Returns**: (none)



### trans_begin(test_mode = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| test_mode | None | FALSE | - |

**Returns**: (none)



### trans_commit()

**Returns**: (none)



### trans_rollback()

**Returns**: (none)



### compile_binds(sql, binds)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |
| binds | None | - | - |

**Returns**: (none)



### is_write_type(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |

**Returns**: (none)



### elapsed_time(decimals = 6)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| decimals | None | 6 | - |

**Returns**: (none)



### total_queries()

**Returns**: (none)



### last_query()

**Returns**: (none)



### escape(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### escape_str(str, like = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| like | None | FALSE | - |

**Returns**: (none)



### escape_like_str(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### _escape_str(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### primary(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### count_all(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |

**Returns**: (none)



### list_tables(constrain_by_prefix = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| constrain_by_prefix | None | FALSE | - |

**Returns**: (none)



### table_exists(table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table_name | None | - | - |

**Returns**: (none)



### list_fields(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### field_exists(field_name, table_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field_name | None | - | - |
| table_name | None | - | - |

**Returns**: (none)



### field_data(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### escape_identifiers(item, split = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| item | None | - | - |
| split | None | TRUE | - |

**Returns**: (none)



### insert_string(table, data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| data | None | - | - |

**Returns**: (none)



### _insert(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |

**Returns**: (none)



### update_string(table, data, where)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| data | None | - | - |
| where | None | - | - |

**Returns**: (none)



### _update(table, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |

**Returns**: (none)



### _has_operator(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### _get_operator(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### call_function(function)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function | None | - | - |

**Returns**: (none)



### cache_set_path(path = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |

**Returns**: (none)



### cache_on()

**Returns**: (none)



### cache_off()

**Returns**: (none)



### cache_delete(segment_one = '', segment_two = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| segment_one | None | '' | - |
| segment_two | None | '' | - |

**Returns**: (none)



### cache_delete_all()

**Returns**: (none)



### _cache_init()

**Returns**: (none)



### close()

**Returns**: (none)



### _close()

**Returns**: (none)



### display_error(error = '', swap = '', native = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| error | None | '' | - |
| swap | None | '' | - |
| native | None | FALSE | - |

**Returns**: (none)



### protect_identifiers(item, prefix_single = FALSE, protect_identifiers = NULL, field_exists = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| item | None | - | - |
| prefix_single | None | FALSE | - |
| protect_identifiers | None | NULL | - |
| field_exists | None | TRUE | - |

**Returns**: (none)



### _reset_select()

**Returns**: (none)


