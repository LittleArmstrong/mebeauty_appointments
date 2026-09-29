# API Reference: text_helper.php

**Language**: PHP

**Source**: `system/helpers/text_helper.php`

---

## Functions

### word_limiter(str, limit = 100, end_char = '&#8230;')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| limit | None | 100 | - |
| end_char | None | '&#8230;' | - |

**Returns**: (none)



### character_limiter(str, n = 500, end_char = '&#8230;')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| n | None | 500 | - |
| end_char | None | '&#8230;' | - |

**Returns**: (none)



### ascii_to_entities(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### entities_to_ascii(str, all = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| all | None | TRUE | - |

**Returns**: (none)



### word_censor(str, censored, replacement = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| censored | None | - | - |
| replacement | None | '' | - |

**Returns**: (none)



### highlight_code(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### highlight_phrase(str, phrase, tag_open = '<mark>', tag_close = '</mark>')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| phrase | None | - | - |
| tag_open | None | '<mark>' | - |
| tag_close | None | '</mark>' | - |

**Returns**: (none)



### convert_accented_characters(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### word_wrap(str, charlim = 76)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| charlim | None | 76 | - |

**Returns**: (none)



### ellipsize(str, max_length, position = 1, ellipsis = '&hellip;')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| max_length | None | - | - |
| position | None | 1 | - |
| ellipsis | None | '&hellip;' | - |

**Returns**: (none)


