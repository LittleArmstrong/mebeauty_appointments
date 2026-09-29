# API Reference: Image_lib.php

**Language**: PHP

**Source**: `system/libraries/Image_lib.php`

---

## Classes

### CI_Image_lib

**Inherits from**: (none)

#### Methods

##### __construct(props = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| props | None | array( | - |


##### clear()


##### initialize(props = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| props | None | array( | - |


##### resize()


##### crop()


##### rotate()


##### if(this->image_library = == 'imagemagick' OR $this->image_library === 'netpbm')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| this->image_library | None | == 'imagemagick' OR $this->image_library === 'netpbm' | - |


##### image_process_gd(action = 'resize')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| action | None | 'resize' | - |


##### image_process_imagemagick(action = 'resize')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| action | None | 'resize' | - |


##### image_process_netpbm(action = 'resize')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| action | None | 'resize' | - |


##### image_rotate_gd()


##### image_mirror_gd()


##### watermark()


##### overlay_watermark()


##### text_watermark()


##### image_create_gd(path = '', image_type = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |
| image_type | None | '' | - |


##### image_save_gd(resource)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| resource | None | - | - |


##### image_display_gd(resource)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| resource | None | - | - |


##### image_reproportion()


##### get_image_properties(path = '', return = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |
| return | None | FALSE | - |


##### size_calculator(vals)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vals | None | - | - |


##### explode_name(source_image)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| source_image | None | - | - |


##### gd_loaded()


##### gd_version()


##### set_error(msg)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |


##### display_errors(open = '<p>', close = '</p>')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| open | None | '<p>' | - |
| close | None | '</p>' | - |




## Functions

### __construct(props = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| props | None | array( | - |

**Returns**: (none)



### clear()

**Returns**: (none)



### initialize(props = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| props | None | array( | - |

**Returns**: (none)



### resize()

**Returns**: (none)



### crop()

**Returns**: (none)



### rotate()

**Returns**: (none)



### if(this->image_library = == 'imagemagick' OR $this->image_library === 'netpbm')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| this->image_library | None | == 'imagemagick' OR $this->image_library === 'netpbm' | - |

**Returns**: (none)



### image_process_gd(action = 'resize')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| action | None | 'resize' | - |

**Returns**: (none)



### image_process_imagemagick(action = 'resize')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| action | None | 'resize' | - |

**Returns**: (none)



### image_process_netpbm(action = 'resize')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| action | None | 'resize' | - |

**Returns**: (none)



### image_rotate_gd()

**Returns**: (none)



### image_mirror_gd()

**Returns**: (none)



### watermark()

**Returns**: (none)



### overlay_watermark()

**Returns**: (none)



### text_watermark()

**Returns**: (none)



### image_create_gd(path = '', image_type = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |
| image_type | None | '' | - |

**Returns**: (none)



### image_save_gd(resource)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| resource | None | - | - |

**Returns**: (none)



### image_display_gd(resource)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| resource | None | - | - |

**Returns**: (none)



### image_reproportion()

**Returns**: (none)



### get_image_properties(path = '', return = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |
| return | None | FALSE | - |

**Returns**: (none)



### size_calculator(vals)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vals | None | - | - |

**Returns**: (none)



### explode_name(source_image)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| source_image | None | - | - |

**Returns**: (none)



### gd_loaded()

**Returns**: (none)



### gd_version()

**Returns**: (none)



### set_error(msg)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |

**Returns**: (none)



### display_errors(open = '<p>', close = '</p>')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| open | None | '<p>' | - |
| close | None | '</p>' | - |

**Returns**: (none)


