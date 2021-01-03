
import moment from 'moment';
import { formatDate } from './formatDate'
export function getDates(startDate, endDate, eventsList) {
    var dates = []
    var currentDate = startDate
    // console.log(currentDate)
    var addDays = function(days) {
        var date = new Date(this.valueOf());
        date.setDate(date.getDate() + days);
        return date;
    };
    while (currentDate <= endDate) {
        dates.push({
            event_date: currentDate});
        currentDate = addDays.call(currentDate, 1);
    }
    dates.map((event_obj, index) => {
        var day = moment(event_obj.event_date).format('YYYY-MM-DD'),
            result = eventsList.filter(el => el.event_date === day)
        if(result !== undefined){
            var arr = []
                result.map(res => {
                    arr.push({event_id: res.id, event_name: res.event_name})
                    // Object.assign(event_obj, {event_id: res.id})
                })
                Object.assign(event_obj, {event_details: arr})
        }
    });
    console.log(dates)
    return dates;
} 

export function updateCal(start, end) {
    var monthStart = moment(start).format('MMM')
    var yearStart = moment(start).format('YYYY')
    var monthEnd = moment(end).format('MMM')
    var yearEnd = moment(end).format('YYYY')
    var convertedStart = moment(start).format('MM YYYY')
    var convertedEnd =  moment(end).format('MM YYYY')
    if (convertedStart === convertedEnd) {
        return `${monthStart} ${yearStart}`
    } else {
        return `${monthStart} ${yearStart} - ${monthEnd} ${yearEnd}`
    }

}