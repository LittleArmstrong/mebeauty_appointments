# API Reference: DB_result.php

**Language**: PHP

**Source**: `system/database/DB_result.php`

---

## Classes

### CI_DB_result

**Inherits from**: (none)

#### Methods

##### __construct(&$driver_object)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$driver_object | None | - | - |


##### num_rows()


##### result(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |


##### custom_result_object(class_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class_name | None | - | - |


##### result_object()


##### result_array()


##### row(n = 0, type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 0 | - |
| type | None | 'object' | - |


##### set_row(key, value = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |


##### custom_row_object(n, type)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| type | None | - | - |


##### row_object(n = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 0 | - |


##### row_array(n = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 0 | - |


##### first_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |


##### last_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |


##### next_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |


##### previous_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |


##### unbuffered_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |


##### num_fields()


##### list_fields()


##### field_data()


##### free_result()


##### data_seek(n = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 0 | - |


##### _fetch_assoc()


##### _fetch_object(class_name = 'stdClass')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class_name | None | 'stdClass' | - |




## Functions

### __construct(&$driver_object)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$driver_object | None | - | - |

**Returns**: (none)



### num_rows()

**Returns**: (none)



### result(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |

**Returns**: (none)



### custom_result_object(class_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class_name | None | - | - |

**Returns**: (none)



### result_object()

**Returns**: (none)



### result_array()

**Returns**: (none)



### row(n = 0, type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 0 | - |
| type | None | 'object' | - |

**Returns**: (none)



### set_row(key, value = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| value | None | NULL | - |

**Returns**: (none)



### custom_row_object(n, type)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| type | None | - | - |

**Returns**: (none)



### row_object(n = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 0 | - |

**Returns**: (none)



### row_array(n = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 0 | - |

**Returns**: (none)



### first_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |

**Returns**: (none)



### last_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |

**Returns**: (none)



### next_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |

**Returns**: (none)



### previous_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |

**Returns**: (none)



### unbuffered_row(type = 'object')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'object' | - |

**Returns**: (none)



### num_fields()

**Returns**: (none)



### list_fields()

**Returns**: (none)



### field_data()

**Returns**: (none)



### free_result()

**Returns**: (none)



### data_seek(n = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 0 | - |

**Returns**: (none)



### _fetch_assoc()

**Returns**: (none)



### _fetch_object(class_name = 'stdClass')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class_name | None | 'stdClass' | - |

**Returns**: (none)


