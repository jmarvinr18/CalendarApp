<template>
    <div>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean">
        <div class="container">
            <a class="navbar-brand" href="#">JMR Calendar</a>
            <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div>
        <div class="container main-section">
            <div class="row">
                <div class="col-sm-4 col-md-6 col-lg-5">
                    <div class="event-setup">
                        <h1>Event Calendar</h1>
                    <div class="d-flex flex-column mb-auto mb-sm-auto mb-md-auto event-name">
                        <label>Event</label>
                        <input type="text" placeholder="Enter event name" v-model="eventOverview.event_name">
                    </div>
                    <div class="row mt-auto mt-xl-auto">
                        <div class="col-6 col-sm-12 col-md-12 col-lg-6 col-xl-6 d-flex flex-column">
                            <label class="d-flex flex-column">From</label>
                            <input placeholder="mm/dd/yyyy" type="date" v-model="eventOverview.from_date" @change="updateCalendar()">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 d-flex flex-column">
                            <div></div><label>To</label><input type="date" v-model="eventOverview.to_date" @change="updateCalendar()">
                        </div>
                    </div>
                    <div class="days-container">
                        <label>Choose Days</label>
                        <div class="d-inline-flex flex-sm-column flex-md-column flex-lg-row flex-xl-row days-chckbox">
                            <div class="form-check" >
                                <PrettyCheck v-for="i in weekDays" :key="i" class="p-default p-curve p-thick" color="primary" :value="i" v-model="wkDayEvent">{{i}}</PrettyCheck>
                            </div>
                        </div>
                    </div>
                    <div class="save-btn">
                        <button class="btn btn-primary" type="button" @click="determineAction()">Save</button>
                        <a href="javascript:void(0)" @click="resetEvent()"><i class="fas fa-redo-alt"></i></a>
                    </div>
                    </div>
                </div>
                <div class="col-sm-8 col-md-6 col-lg-7 table-container">
                    <h1 class="cal-table-header">{{monthAndYear}}</h1>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr v-for="(d, i) in newDates" :key="i">
                                    <td class="align-middle date-cell">{{formatDate(d.event_date, num)}} {{formatDate(d.event_date, str)}} </td>
                                    <td class="event-cell"><a class="event" href="javascript:void(0)" v-for="(detail, index) in d.event_details" :key="index" @click="showEventDetails(detail.event_id)"><p class="event-box box effect2 ">{{detail.event_name}}</p></a></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>


<script>

import calendar from '../../api/calendar.js'
import moment from 'moment'
import PrettyCheck from 'pretty-checkbox-vue/check';

import { getDates, updateCal } from '../utils/getDates.js'
import { formatDate } from '../utils/formatDate'
import { completedNotification } from '../utils/alert'
import constant, { weekDays } from '../constants/contants.js'
    export default { 
        data () {
            return {
                str: 'str',
                num: 'num',
                yr: 'yr',
                mon: 'mon',
                eventOverview: {
                    event_name: '',
                    from_date: '',
                    to_date: ''
                },
                events: '',
                dates: [],
                weekDays: weekDays,
                monthAndYear: '',
                wkDayEvent: [],
                eventsList: [],
                method: constant.NEW,
                event_id: ''
            }
        },
        components: {
            PrettyCheck
        },
        mounted() {
           this.showCalendar()
        },
        computed: {
            newDates(){
                return this.dates
            }
        },
        methods: {
            async showEventDetails(id) {
                const response = await calendar.checkEventById(id),
                      eventDetails = response.data.event_details

                this.eventOverview = {
                    event_name: eventDetails.event_name,
                    from_date: eventDetails.from,
                    to_date: eventDetails.to
                }
                this.event_id = id
                this.populateDaysCheckbox(eventDetails)
                this.method = constant.EDIT
            },
            populateDaysCheckbox (eventDetails) {
                this.wkDayEvent = []
                eventDetails.eventdays.map(
                    day => {
                        var eventDateInDayFormat = moment(day.event_date).format('ddd')
                        if (!this.wkDayEvent.includes(eventDateInDayFormat)) {
                            this.wkDayEvent.push(eventDateInDayFormat)
                        }               
                    }
                )
            },
            async deleteEvent() {
                const event_id = this.event_id
                      await calendar.deleteEvent(event_id)
            },
             checkEventDate (start, end) {
                var start = moment(start),
                    end   = moment(end),
                    eventObj = []

                this.wkDayEvent.map(wk => {
                    var endDate = new Date(end);
                    for (var d = new Date(start); d <= endDate; d.setDate(d.getDate() + 1)) {
                        var day = moment(d).format('ddd')
                        if (wk === day) {
                            eventObj.push(moment(d).format('YYYY-MM-DD'))
                        }
                    }
                })        
                return eventObj
            },
            determineAction () {

                this.updateCalendar()
                var start = this.eventOverview.from_date,
                    end = this.eventOverview.to_date,
                    event_name = this.eventOverview.event_name
                // alert(event_name.length)
                if (start.length && end.length && event_name.length > 0) {
                    switch (this.method) {
                        case constant.NEW:
                            this.addEvent(start, end)
                            break;
                        case constant.EDIT:
                            this.deleteEvent()
                            this.updateEvent(start, end)
                            break;                    
                        default:
                            break;
                    }
                    this.showCalendar()
                } else {
                    completedNotification(constant.FIELD_STILL_EMPTY, constant.ERROR)
                }

            },
            async addEvent(start, end){
                var eventEachDates = this.checkEventDate(start, end),
                    eventRes = await calendar.saveEvents(eventEachDates, this.eventOverview)
                this.event_id = eventRes.data.event_id
                completedNotification(constant.EVENT_ADDED, constant.SUCCESS)
                this.method = constant.EDIT
            },
            async updateEvent (start, end){
                var eventEachDates = this.checkEventDate(start, end),
                    response = await calendar.updateEvents(this.event_id, eventEachDates, this.eventOverview)
                    completedNotification(constant.EVENT_UPDATED, constant.SUCCESS)
            },
            async showCalendar () {
                var date = new Date(),
                    firstDay =  new Date(date.getFullYear(), date.getMonth(), 1),
                    lastDay =  new Date(date.getFullYear(), date.getMonth() + 1, 0),
                    year = date.getFullYear(),
                    month = formatDate(date, this.mon)

                const response = await calendar.getEvents()
                this.eventsList = response.data

                this.dates = getDates(firstDay, lastDay, this.eventsList); 
                this.monthAndYear = `${month} ${year}`
            },
            updateCalendar(){
                var start = this.eventOverview.from_date,
                    end = this.eventOverview.to_date

                if (start.length && end.length > 0) {
                    if (start > end) {
                        completedNotification(constant.INVALID_DATE_RANGE, constant.ERROR)
                        this.eventOverview = {
                            from_date: '',
                            to_date: ''
                        }
                    } else {
                        this.monthAndYear = updateCal(start, end)
                        this.dates = getDates(new Date(start), new Date(end), this.eventsList)
                    }
                } 
            },
            formatDate(date, type) {
                return formatDate(date, type)
            },
            resetEvent(){
                this.eventOverview = {
                    from_date: '',
                    to_date: ''
                }
                this.wkDayEvent = []
                this.eventsList = []
                this.showCalendar()
            }
        },
    }
</script>       