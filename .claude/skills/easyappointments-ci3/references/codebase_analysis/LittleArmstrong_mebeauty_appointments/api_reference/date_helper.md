# API Reference: date_helper.php

**Language**: PHP

**Source**: `system/helpers/date_helper.php`

---

## Functions

### now(timezone = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| timezone | None | NULL | - |

**Returns**: (none)



### mdate(datestr = '', time = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| datestr | None | '' | - |
| time | None | '' | - |

**Returns**: (none)



### timespan(seconds = 1, time = '', units = 7)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| seconds | None | 1 | - |
| time | None | '' | - |
| units | None | 7 | - |

**Returns**: (none)



### days_in_month(month = 0, year = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| month | None | 0 | - |
| year | None | '' | - |

**Returns**: (none)



### local_to_gmt(time = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | '' | - |

**Returns**: (none)



### gmt_to_local(time = '', timezone = 'UTC', dst = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | '' | - |
| timezone | None | 'UTC' | - |
| dst | None | FALSE | - |

**Returns**: (none)



### mysql_to_unix(time = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | '' | - |

**Returns**: (none)



### unix_to_human(time = '', seconds = FALSE, fmt = 'us')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | '' | - |
| seconds | None | FALSE | - |
| fmt | None | 'us' | - |

**Returns**: (none)



### human_to_unix(datestr = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| datestr | None | '' | - |

**Returns**: (none)



### nice_date(bad_date = '', format = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| bad_date | None | '' | - |
| format | None | FALSE | - |

**Returns**: (none)



### timezone_menu(default = 'UTC', class = '', name = 'timezones', attributes = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| default | None | 'UTC' | - |
| class | None | '' | - |
| name | None | 'timezones' | - |
| attributes | None | '' | - |

**Returns**: (none)



### timezones(tz = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| tz | None | '' | - |

**Returns**: (none)



### date_range(unix_start = '', mixed = '', is_unix = TRUE, format = 'Y-m-d')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unix_start | None | '' | - |
| mixed | None | '' | - |
| is_unix | None | TRUE | - |
| format | None | 'Y-m-d' | - |

**Returns**: (none)


