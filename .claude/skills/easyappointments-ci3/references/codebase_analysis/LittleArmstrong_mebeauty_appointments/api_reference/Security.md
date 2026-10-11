# API Reference: Security.php

**Language**: PHP

**Source**: `system/core/Security.php`

---

## Classes

### CI_Security

**Inherits from**: (none)

#### Methods

##### __construct(charset)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| charset | None | - | - |


##### csrf_verify()


##### csrf_set_cookie()


##### csrf_show_error()


##### get_csrf_hash()


##### get_csrf_token_name()


##### xss_clean(str, is_image = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| is_image | None | FALSE | - |


##### xss_hash()


##### get_random_bytes(length)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| length | None | - | - |


##### entity_decode(str, charset = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| charset | None | NULL | - |


##### sanitize_filename(str, relative_path = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| relative_path | None | FALSE | - |


##### strip_image_tags(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### _urldecodespaces(matches)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| matches | None | - | - |


##### _compact_exploded_words(matches)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| matches | None | - | - |


##### _sanitize_naughty_html(matches)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| matches | None | - | - |


##### _js_link_removal(match)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| match | None | - | - |


##### _js_img_removal(match)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| match | None | - | - |


##### _convert_attribute(match)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| match | None | - | - |


##### _filter_attributes(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### _decode_entity(match)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| match | None | - | - |


##### _do_never_allowed(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### _csrf_set_hash()




## Functions

### __construct(charset)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| charset | None | - | - |

**Returns**: (none)



### csrf_verify()

**Returns**: (none)



### csrf_set_cookie()

**Returns**: (none)



### csrf_show_error()

**Returns**: (none)



### get_csrf_hash()

**Returns**: (none)



### get_csrf_token_name()

**Returns**: (none)



### xss_clean(str, is_image = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| is_image | None | FALSE | - |

**Returns**: (none)



### xss_hash()

**Returns**: (none)



### get_random_bytes(length)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| length | None | - | - |

**Returns**: (none)



### entity_decode(str, charset = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| charset | None | NULL | - |

**Returns**: (none)



### sanitize_filename(str, relative_path = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| relative_path | None | FALSE | - |

**Returns**: (none)



### strip_image_tags(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### _urldecodespaces(matches)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| matches | None | - | - |

**Returns**: (none)



### _compact_exploded_words(matches)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| matches | None | - | - |

**Returns**: (none)



### _sanitize_naughty_html(matches)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| matches | None | - | - |

**Returns**: (none)



### _js_link_removal(match)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| match | None | - | - |

**Returns**: (none)



### _js_img_removal(match)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| match | None | - | - |

**Returns**: (none)



### _convert_attribute(match)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| match | None | - | - |

**Returns**: (none)



### _filter_attributes(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### _decode_entity(match)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| match | None | - | - |

**Returns**: (none)



### _do_never_allowed(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### _csrf_set_hash()

**Returns**: (none)


