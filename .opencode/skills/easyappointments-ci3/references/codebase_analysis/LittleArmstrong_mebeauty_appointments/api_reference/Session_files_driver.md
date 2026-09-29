# API Reference: Session_files_driver.php

**Language**: PHP

**Source**: `system/libraries/Session/drivers/Session_files_driver.php`

---

## Classes

### CI_Session_files_driver

**Inherits from**: CI_Session_driver, SessionHandlerInterface

#### Methods

##### __construct(&$params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$params | None | - | - |


##### open(save_path, name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| save_path | None | - | - |
| name | None | - | - |


##### read(session_id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| session_id | None | - | - |


##### write(session_id, session_data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| session_id | None | - | - |
| session_data | None | - | - |


##### close()


##### destroy(session_id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| session_id | None | - | - |


##### gc(maxlifetime)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| maxlifetime | None | - | - |


##### validateSessionId(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |


##### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |




## Functions

### __construct(&$params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$params | None | - | - |

**Returns**: (none)



### open(save_path, name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| save_path | None | - | - |
| name | None | - | - |

**Returns**: (none)



### read(session_id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| session_id | None | - | - |

**Returns**: (none)



### write(session_id, session_data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| session_id | None | - | - |
| session_data | None | - | - |

**Returns**: (none)



### close()

**Returns**: (none)



### destroy(session_id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| session_id | None | - | - |

**Returns**: (none)



### gc(maxlifetime)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| maxlifetime | None | - | - |

**Returns**: (none)



### validateSessionId(id)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | None | - | - |

**Returns**: (none)



### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)


