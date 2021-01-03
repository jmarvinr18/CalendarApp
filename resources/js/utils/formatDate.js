import moment from 'moment'

export function formatDate (date, type) {
    switch (type) {
        case 'num':
            return moment(date).format('DD')
        case 'str':
            return moment(date).format('ddd')
        case 'mon':
            return moment(date).format('MMM')
        default:
            break;
    }
}