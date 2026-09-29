# API Reference: Xmlrpcs.php

**Language**: PHP

**Source**: `system/libraries/Xmlrpcs.php`

---

## Classes

### CI_Xmlrpcs

**Inherits from**: CI_Xmlrpc

#### Methods

##### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |


##### initialize(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |


##### set_system_methods()


##### serve()


##### add_to_map(methodname, function, sig, doc)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| methodname | None | - | - |
| function | None | - | - |
| sig | None | - | - |
| doc | None | - | - |


##### parseRequest(data = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |


##### _execute(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |


##### listMethods(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |


##### methodSignature(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |


##### methodHelp(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |


##### multicall(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |


##### multicall_error(err)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| err | None | - | - |


##### do_multicall(call)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| call | None | - | - |




## Functions

### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |

**Returns**: (none)



### initialize(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |

**Returns**: (none)



### set_system_methods()

**Returns**: (none)



### serve()

**Returns**: (none)



### add_to_map(methodname, function, sig, doc)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| methodname | None | - | - |
| function | None | - | - |
| sig | None | - | - |
| doc | None | - | - |

**Returns**: (none)



### parseRequest(data = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |

**Returns**: (none)



### _execute(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |

**Returns**: (none)



### listMethods(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |

**Returns**: (none)



### methodSignature(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |

**Returns**: (none)



### methodHelp(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |

**Returns**: (none)



### multicall(m)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| m | None | - | - |

**Returns**: (none)



### multicall_error(err)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| err | None | - | - |

**Returns**: (none)



### do_multicall(call)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| call | None | - | - |

**Returns**: (none)


