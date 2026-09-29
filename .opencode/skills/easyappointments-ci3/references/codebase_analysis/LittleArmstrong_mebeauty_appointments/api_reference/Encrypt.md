# API Reference: Encrypt.php

**Language**: PHP

**Source**: `system/libraries/Encrypt.php`

---

## Classes

### CI_Encrypt

**Inherits from**: (none)

#### Methods

##### __construct()


##### get_key(key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | '' | - |


##### set_key(key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | '' | - |


##### encode(string, key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| key | None | '' | - |


##### decode(string, key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| key | None | '' | - |


##### encode_from_legacy(string, legacy_mode = MCRYPT_MODE_ECB, key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| legacy_mode | None | MCRYPT_MODE_ECB | - |
| key | None | '' | - |


##### _xor_decode(string, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| key | None | - | - |


##### _xor_merge(string, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| key | None | - | - |


##### mcrypt_encode(data, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| key | None | - | - |


##### mcrypt_decode(data, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| key | None | - | - |


##### _add_cipher_noise(data, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| key | None | - | - |


##### _remove_cipher_noise(data, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| key | None | - | - |


##### set_cipher(cipher)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cipher | None | - | - |


##### set_mode(mode)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| mode | None | - | - |


##### _get_cipher()


##### _get_mode()


##### set_hash(type = 'sha1')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'sha1' | - |


##### hash(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### substr(str, start, length = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| start | None | - | - |
| length | None | NULL | - |




## Functions

### __construct()

**Returns**: (none)



### get_key(key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | '' | - |

**Returns**: (none)



### set_key(key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | '' | - |

**Returns**: (none)



### encode(string, key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| key | None | '' | - |

**Returns**: (none)



### decode(string, key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| key | None | '' | - |

**Returns**: (none)



### encode_from_legacy(string, legacy_mode = MCRYPT_MODE_ECB, key = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| legacy_mode | None | MCRYPT_MODE_ECB | - |
| key | None | '' | - |

**Returns**: (none)



### _xor_decode(string, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| key | None | - | - |

**Returns**: (none)



### _xor_merge(string, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| string | None | - | - |
| key | None | - | - |

**Returns**: (none)



### mcrypt_encode(data, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| key | None | - | - |

**Returns**: (none)



### mcrypt_decode(data, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| key | None | - | - |

**Returns**: (none)



### _add_cipher_noise(data, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| key | None | - | - |

**Returns**: (none)



### _remove_cipher_noise(data, key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| key | None | - | - |

**Returns**: (none)



### set_cipher(cipher)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cipher | None | - | - |

**Returns**: (none)



### set_mode(mode)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| mode | None | - | - |

**Returns**: (none)



### _get_cipher()

**Returns**: (none)



### _get_mode()

**Returns**: (none)



### set_hash(type = 'sha1')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| type | None | 'sha1' | - |

**Returns**: (none)



### hash(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### substr(str, start, length = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| start | None | - | - |
| length | None | NULL | - |

**Returns**: (none)


