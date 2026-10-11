# API Reference: calendar_table_view.js

**Language**: JavaScript

**Source**: `assets/js/utils/calendar_table_view.js`

---

## Functions

### getCalendarHeight()

**Returns**: (none)



### closePopover()

**Returns**: (none)



### isUnavailability(eventData)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| eventData | None | - | - |

**Returns**: (none)



### isWorkingPlanException(eventData)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| eventData | None | - | - |

**Returns**: (none)



### isBlockedPeriod(eventData)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| eventData | None | - | - |

**Returns**: (none)



### getAvailableProviders()

**Returns**: (none)



### getFormatSettings()

**Returns**: (none)



### populateAppointmentModal(appointment)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | None | - | - |

**Returns**: (none)



### populateUnavailabilityModal(unavailability)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | None | - | - |

**Returns**: (none)



### updateProviderWorkingPlanExceptions(providerId, workingPlanExceptions)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| providerId | None | - | - |
| workingPlanExceptions | None | - | - |

**Returns**: (none)



### handleEditWorkingPlanException(data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |

**Returns**: (none)



### handleDeleteWorkingPlanException(eventData)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| eventData | None | - | - |

**Returns**: (none)



### handleDeleteAppointment(appointmentId)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointmentId | None | - | - |

**Returns**: (none)



### onEditPopoverClick()

**Returns**: (none)



### onDeletePopoverClick()

**Returns**: (none)



### onEventClick(info)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |

**Returns**: (none)



### onEventResize(info)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |

**Returns**: (none)



### handleAppointmentResize(info, eventData)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |
| eventData | None | - | - |

**Returns**: (none)



### handleUnavailabilityResize(info, eventData)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |
| eventData | None | - | - |

**Returns**: (none)



### onEventDrop(info)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |

**Returns**: (none)



### handleAppointmentDrop(info, eventData)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |
| eventData | None | - | - |

**Returns**: (none)



### handleUnavailabilityDrop(info, eventData)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |
| eventData | None | - | - |

**Returns**: (none)



### prepareAppointmentForSave(eventData)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| eventData | None | - | - |

**Returns**: (none)



### showNotifyUsersDialog(appointment, successCallback, revertCallback)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | None | - | - |
| successCallback | None | - | - |
| revertCallback | None | - | - |

**Returns**: (none)



### onSelect(info, fullCalendar)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |
| fullCalendar | None | - | - |

**Returns**: (none)



### createAppointments($providerColumn, appointments)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $providerColumn | None | - | - |
| appointments | None | - | - |

**Returns**: (none)



### createUnavailabilities($providerColumn, unavailabilities)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $providerColumn | None | - | - |
| unavailabilities | None | - | - |

**Returns**: (none)



### createBlockedPeriods($providerColumn, blockedPeriods)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $providerColumn | None | - | - |
| blockedPeriods | None | - | - |

**Returns**: (none)



### createNonWorkingHours($calendar, provider)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $calendar | None | - | - |
| provider | None | - | - |

**Returns**: (none)



### createBreaks($providerColumn, breaks)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $providerColumn | None | - | - |
| breaks | None | - | - |

**Returns**: (none)



### createHeader()

**Returns**: (none)



### createView(startDate, endDate)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| startDate | None | - | - |
| endDate | None | - | - |

**Returns**: (none)



### createDateColumn($wrapper, date, events)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $wrapper | None | - | - |
| date | None | - | - |
| events | None | - | - |

**Returns**: (none)



### createProviderColumn($dateColumn, date, provider, events)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $dateColumn | None | - | - |
| date | None | - | - |
| provider | None | - | - |
| events | None | - | - |

**Returns**: (none)



### createCalendar($providerColumn, goToDate, provider)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $providerColumn | None | - | - |
| goToDate | None | - | - |
| provider | None | - | - |

**Returns**: (none)



### setCalendarViewSize()

**Returns**: (none)



### addEventListeners()

**Returns**: (none)



### initialize()

**Returns**: (none)



### successCallback(response)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| response | None | - | - |

**Returns**: (none)



### successCallback()

**Returns**: (none)



### successCallback(notifyUsers)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| notifyUsers | None | - | - |

**Returns**: (none)



### undoFunction()

**Returns**: (none)



### successCallback()

**Returns**: (none)



### undoFunction()

**Returns**: (none)



### successCallback(notifyUsers)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| notifyUsers | None | - | - |

**Returns**: (none)



### undoFunction()

**Returns**: (none)



### successCallback()

**Returns**: (none)



### undoFunction()

**Returns**: (none)


