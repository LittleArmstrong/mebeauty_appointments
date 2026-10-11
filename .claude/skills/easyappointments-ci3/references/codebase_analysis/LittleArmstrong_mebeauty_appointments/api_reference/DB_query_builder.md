# API Reference: DB_query_builder.php

**Language**: PHP

**Source**: `system/database/DB_query_builder.php`

---

## Classes

### CI_DB_query_builder

**Inherits from**: CI_DB_driver

#### Methods

##### select(select = '*', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '*' | - |
| escape | None | NULL | - |


##### select_max(select = '', alias = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |


##### select_min(select = '', alias = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |


##### select_avg(select = '', alias = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |


##### select_sum(select = '', alias = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |


##### _max_min_avg_sum(select = '', alias = '', type = 'MAX')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |
| type | None | 'MAX' | - |


##### _create_alias_from_table(item)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| item | None | - | - |


##### distinct(val = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | TRUE | - |


##### from(from)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| from | None | - | - |


##### join(table, cond, type = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| cond | None | - | - |
| type | None | '' | - |
| escape | None | NULL | - |


##### where(key, value = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |
| escape | None | NULL | - |


##### or_where(key, value = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |
| escape | None | NULL | - |


##### _wh(qb_key, key, value = NULL, type = 'AND ', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| qb_key | None | - | - |
| key | None | - | - |
| value | None | NULL | - |
| type | None | 'AND ' | - |
| escape | None | NULL | - |


##### where_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |


##### or_where_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |


##### where_not_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |


##### or_where_not_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |


##### having_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |


##### or_having_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |


##### having_not_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |


##### or_having_not_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |


##### _wh_in(qb_key, key, values: array, not = FALSE, type = 'AND ', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| qb_key | None | - | - |
| key | None | - | - |
| values | array | - | - |
| not | None | FALSE | - |
| type | None | 'AND ' | - |
| escape | None | NULL | - |


##### like(field, match = '', side = 'both', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| side | None | 'both' | - |
| escape | None | NULL | - |


##### not_like(field, match = '', side = 'both', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| side | None | 'both' | - |
| escape | None | NULL | - |


##### or_like(field, match = '', side = 'both', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| side | None | 'both' | - |
| escape | None | NULL | - |


##### or_not_like(field, match = '', side = 'both', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| side | None | 'both' | - |
| escape | None | NULL | - |


##### _like(field, match = '', type = 'AND ', side = 'both', not = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| type | None | 'AND ' | - |
| side | None | 'both' | - |
| not | None | '' | - |
| escape | None | NULL | - |


##### group_start(not = '', type = 'AND ')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| not | None | '' | - |
| type | None | 'AND ' | - |


##### or_group_start()


##### not_group_start()


##### or_not_group_start()


##### group_end()


##### _group_get_type(type)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | - | - |


##### group_by(by, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| by | None | - | - |
| escape | None | NULL | - |


##### having(key, value = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |
| escape | None | NULL | - |


##### or_having(key, value = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |
| escape | None | NULL | - |


##### order_by(orderby, direction = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| orderby | None | - | - |
| direction | None | '' | - |
| escape | None | NULL | - |


##### limit(value, offset = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| value | None | - | - |
| offset | None | 0 | - |


##### offset(offset)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| offset | None | - | - |


##### _limit(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |


##### set(key, value = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | '' | - |
| escape | None | NULL | - |


##### get_compiled_select(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |


##### get(table = '', limit = NULL, offset = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| limit | None | NULL | - |
| offset | None | NULL | - |


##### count_all_results(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |


##### get_where(table = '', where = NULL, limit = NULL, offset = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| where | None | NULL | - |
| limit | None | NULL | - |
| offset | None | NULL | - |


##### insert_batch(table, set = NULL, escape = NULL, batch_size = 100)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| set | None | NULL | - |
| escape | None | NULL | - |
| batch_size | None | 100 | - |


##### _insert_batch(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |


##### set_insert_batch(key, value = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | '' | - |
| escape | None | NULL | - |


##### get_compiled_insert(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |


##### insert(table = '', set = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| set | None | NULL | - |
| escape | None | NULL | - |


##### _validate_insert(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |


##### replace(table = '', set = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| set | None | NULL | - |


##### _replace(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |


##### _from_tables()


##### get_compiled_update(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |


##### update(table = '', set = NULL, where = NULL, limit = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| set | None | NULL | - |
| where | None | NULL | - |
| limit | None | NULL | - |


##### _validate_update(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### update_batch(table, set = NULL, index = NULL, batch_size = 100)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| set | None | NULL | - |
| index | None | NULL | - |
| batch_size | None | 100 | - |


##### _update_batch(table, values, index)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |
| index | None | - | - |


##### set_update_batch(key, index = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| index | None | '' | - |
| escape | None | NULL | - |


##### empty_table(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |


##### truncate(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |


##### _truncate(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### get_compiled_delete(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |


##### delete(table = '', where = '', limit = NULL, reset_data = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| where | None | '' | - |
| limit | None | NULL | - |
| reset_data | None | TRUE | - |


##### _delete(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### dbprefix(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |


##### set_dbprefix(prefix = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| prefix | None | '' | - |


##### _track_aliases(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |


##### _compile_select(select_override = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select_override | None | FALSE | - |


##### _compile_wh(qb_key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| qb_key | None | - | - |


##### _compile_group_by()


##### _compile_order_by()


##### _object_to_array(object)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| object | None | - | - |


##### _object_to_array_batch(object)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| object | None | - | - |


##### start_cache()


##### stop_cache()


##### flush_cache()


##### _merge_cache()


##### _is_literal(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### reset_query()


##### _reset_run(qb_reset_items)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| qb_reset_items | None | - | - |


##### _reset_select()


##### _reset_write()




## Functions

### select(select = '*', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '*' | - |
| escape | None | NULL | - |

**Returns**: (none)



### select_max(select = '', alias = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |

**Returns**: (none)



### select_min(select = '', alias = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |

**Returns**: (none)



### select_avg(select = '', alias = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |

**Returns**: (none)



### select_sum(select = '', alias = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |

**Returns**: (none)



### _max_min_avg_sum(select = '', alias = '', type = 'MAX')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select | None | '' | - |
| alias | None | '' | - |
| type | None | 'MAX' | - |

**Returns**: (none)



### _create_alias_from_table(item)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| item | None | - | - |

**Returns**: (none)



### distinct(val = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | TRUE | - |

**Returns**: (none)



### from(from)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| from | None | - | - |

**Returns**: (none)



### join(table, cond, type = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| cond | None | - | - |
| type | None | '' | - |
| escape | None | NULL | - |

**Returns**: (none)



### where(key, value = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |
| escape | None | NULL | - |

**Returns**: (none)



### or_where(key, value = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |
| escape | None | NULL | - |

**Returns**: (none)



### _wh(qb_key, key, value = NULL, type = 'AND ', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| qb_key | None | - | - |
| key | None | - | - |
| value | None | NULL | - |
| type | None | 'AND ' | - |
| escape | None | NULL | - |

**Returns**: (none)



### where_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |

**Returns**: (none)



### or_where_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |

**Returns**: (none)



### where_not_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |

**Returns**: (none)



### or_where_not_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |

**Returns**: (none)



### having_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |

**Returns**: (none)



### or_having_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |

**Returns**: (none)



### having_not_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |

**Returns**: (none)



### or_having_not_in(key, values: array, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| values | array | - | - |
| escape | None | NULL | - |

**Returns**: (none)



### _wh_in(qb_key, key, values: array, not = FALSE, type = 'AND ', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| qb_key | None | - | - |
| key | None | - | - |
| values | array | - | - |
| not | None | FALSE | - |
| type | None | 'AND ' | - |
| escape | None | NULL | - |

**Returns**: (none)



### like(field, match = '', side = 'both', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| side | None | 'both' | - |
| escape | None | NULL | - |

**Returns**: (none)



### not_like(field, match = '', side = 'both', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| side | None | 'both' | - |
| escape | None | NULL | - |

**Returns**: (none)



### or_like(field, match = '', side = 'both', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| side | None | 'both' | - |
| escape | None | NULL | - |

**Returns**: (none)



### or_not_like(field, match = '', side = 'both', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| side | None | 'both' | - |
| escape | None | NULL | - |

**Returns**: (none)



### _like(field, match = '', type = 'AND ', side = 'both', not = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| match | None | '' | - |
| type | None | 'AND ' | - |
| side | None | 'both' | - |
| not | None | '' | - |
| escape | None | NULL | - |

**Returns**: (none)



### group_start(not = '', type = 'AND ')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| not | None | '' | - |
| type | None | 'AND ' | - |

**Returns**: (none)



### or_group_start()

**Returns**: (none)



### not_group_start()

**Returns**: (none)



### or_not_group_start()

**Returns**: (none)



### group_end()

**Returns**: (none)



### _group_get_type(type)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | - | - |

**Returns**: (none)



### group_by(by, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| by | None | - | - |
| escape | None | NULL | - |

**Returns**: (none)



### having(key, value = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |
| escape | None | NULL | - |

**Returns**: (none)



### or_having(key, value = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |
| escape | None | NULL | - |

**Returns**: (none)



### order_by(orderby, direction = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| orderby | None | - | - |
| direction | None | '' | - |
| escape | None | NULL | - |

**Returns**: (none)



### limit(value, offset = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| value | None | - | - |
| offset | None | 0 | - |

**Returns**: (none)



### offset(offset)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| offset | None | - | - |

**Returns**: (none)



### _limit(sql)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sql | None | - | - |

**Returns**: (none)



### set(key, value = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | '' | - |
| escape | None | NULL | - |

**Returns**: (none)



### get_compiled_select(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |

**Returns**: (none)



### get(table = '', limit = NULL, offset = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| limit | None | NULL | - |
| offset | None | NULL | - |

**Returns**: (none)



### count_all_results(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |

**Returns**: (none)



### get_where(table = '', where = NULL, limit = NULL, offset = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| where | None | NULL | - |
| limit | None | NULL | - |
| offset | None | NULL | - |

**Returns**: (none)



### insert_batch(table, set = NULL, escape = NULL, batch_size = 100)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| set | None | NULL | - |
| escape | None | NULL | - |
| batch_size | None | 100 | - |

**Returns**: (none)



### _insert_batch(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |

**Returns**: (none)



### set_insert_batch(key, value = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | '' | - |
| escape | None | NULL | - |

**Returns**: (none)



### get_compiled_insert(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |

**Returns**: (none)



### insert(table = '', set = NULL, escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| set | None | NULL | - |
| escape | None | NULL | - |

**Returns**: (none)



### _validate_insert(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |

**Returns**: (none)



### replace(table = '', set = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| set | None | NULL | - |

**Returns**: (none)



### _replace(table, keys, values)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| keys | None | - | - |
| values | None | - | - |

**Returns**: (none)



### _from_tables()

**Returns**: (none)



### get_compiled_update(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |

**Returns**: (none)



### update(table = '', set = NULL, where = NULL, limit = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| set | None | NULL | - |
| where | None | NULL | - |
| limit | None | NULL | - |

**Returns**: (none)



### _validate_update(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### update_batch(table, set = NULL, index = NULL, batch_size = 100)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| set | None | NULL | - |
| index | None | NULL | - |
| batch_size | None | 100 | - |

**Returns**: (none)



### _update_batch(table, values, index)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |
| values | None | - | - |
| index | None | - | - |

**Returns**: (none)



### set_update_batch(key, index = '', escape = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| index | None | '' | - |
| escape | None | NULL | - |

**Returns**: (none)



### empty_table(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |

**Returns**: (none)



### truncate(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |

**Returns**: (none)



### _truncate(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### get_compiled_delete(table = '', reset = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| reset | None | TRUE | - |

**Returns**: (none)



### delete(table = '', where = '', limit = NULL, reset_data = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |
| where | None | '' | - |
| limit | None | NULL | - |
| reset_data | None | TRUE | - |

**Returns**: (none)



### _delete(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### dbprefix(table = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | '' | - |

**Returns**: (none)



### set_dbprefix(prefix = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| prefix | None | '' | - |

**Returns**: (none)



### _track_aliases(table)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| table | None | - | - |

**Returns**: (none)



### _compile_select(select_override = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| select_override | None | FALSE | - |

**Returns**: (none)



### _compile_wh(qb_key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| qb_key | None | - | - |

**Returns**: (none)



### _compile_group_by()

**Returns**: (none)



### _compile_order_by()

**Returns**: (none)



### _object_to_array(object)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| object | None | - | - |

**Returns**: (none)



### _object_to_array_batch(object)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| object | None | - | - |

**Returns**: (none)



### start_cache()

**Returns**: (none)



### stop_cache()

**Returns**: (none)



### flush_cache()

**Returns**: (none)



### _merge_cache()

**Returns**: (none)



### _is_literal(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### reset_query()

**Returns**: (none)



### _reset_run(qb_reset_items)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| qb_reset_items | None | - | - |

**Returns**: (none)



### _reset_select()

**Returns**: (none)



### _reset_write()

**Returns**: (none)


