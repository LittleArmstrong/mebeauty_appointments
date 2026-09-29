# API Reference: calendar_default_view.js

**Language**: JavaScript

**Source**: `assets/js/utils/calendar_default_view.js`

---

## Functions

### getCalendarHeight()

**Returns**: (none)



### getSelectedFilterType()

**Returns**: (none)



### isProviderFilter()

**Returns**: (none)



### findProvider(providerId)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| providerId | None | - | - |

**Returns**: (none)



### findService(serviceId)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| serviceId | None | - | - |

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



### handleDeleteWorkingPlanException()

**Returns**: (none)



### onEditPopoverClick()

**Returns**: (none)



### onDeletePopoverClick()

**Returns**: (none)



### handleDeleteAppointment(appointmentId)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointmentId | None | - | - |

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



### onDateClick(info)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |

**Returns**: (none)



### onSelect(info)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| info | None | - | - |

**Returns**: (none)



### preselectServiceAndProvider()

**Returns**: (none)



### onWindowResize()

**Returns**: (none)



### onDatesSet()

**Returns**: (none)



### refreshCalendarAppointments($calendar, recordId, filterType, startDate, endDate)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| $calendar | None | - | - |
| recordId | None | - | - |
| filterType | None | - | - |
| startDate | None | - | - |
| endDate | None | - | - |

**Returns**: (none)



### createAppointmentEvents(appointments)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointments | None | - | - |

**Returns**: (none)



### createUnavailabilityEvents(unavailabilities)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailabilities | None | - | - |

**Returns**: (none)



### createBlockedPeriodEvents(blockedPeriods)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blockedPeriods | None | - | - |

**Returns**: (none)



### createWorkingPlanEvents(recordId)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| recordId | None | - | - |

**Returns**: (none)



### createWorkingPlanExceptionEvent(date, exception, provider, originalException)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | None | - | - |
| exception | None | - | - |
| provider | None | - | - |
| originalException | None | - | - |

**Returns**: (none)



### createNonWorkingDayEvent(calendarDate)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| calendarDate | None | - | - |

**Returns**: (none)



### createWorkHoursUnavailability(calendarDate, dayPlan, viewEnd)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| calendarDate | None | - | - |
| dayPlan | None | - | - |
| viewEnd | None | - | - |

**Returns**: (none)



### createBreakEvents(calendarDate, breaks)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| calendarDate | None | - | - |
| breaks | None | - | - |

**Returns**: (none)



### addEventListeners()

**Returns**: (none)



### getFormatSettings()

**Returns**: (none)



### populateFilterDropdown()

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


