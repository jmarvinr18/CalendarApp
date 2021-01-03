import { completedNotification } from './alert'
import constant from '../constants/contants.js'
export function validate(eventName, fromDate, toDate) {
    var isValid = true

    if (fromDate.length === 0) {
        completedNotification(constant.FROM_DATE_FIELD_EMPTY)
        isValid = false
    } else if (toDate.length === 0) {
        completedNotification(constant.TO_DATE_FIELD_EMPTY)
        isValid = false
    } else if (eventName.length === 0) {
        completedNotification(constant.EVENT_NAME_FIELD_EMPTY)
        isValid = false
    } else {
        isValid = true
    }
    return isValid
}