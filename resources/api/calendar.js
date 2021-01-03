import api from './api'

export default {
    getEvents (id) {
        return api().get(`/calendar`)
    },
    saveEvents (event, eventOverview) {
        return new Promise(function (resolve, reject) {
            api().post('/calendar', { event_dates: event, event_overview:  eventOverview} )
              .then(response => resolve(response))
              .catch(error => reject(error))
          })
    },
    updateEvents (id, event, eventOverview) {
        return new Promise(function (resolve, reject) {
            api().put(`/calendar/${id}`, { event_dates: event, event_overview:  eventOverview } )
              .then(response => resolve(response))
              .catch(error => reject(error))
          })
    },
    deleteEvent (id) {
        return new Promise(function (resolve, reject) {
            api().delete(`/event-days/${id}`)
            .then(response => resolve(response))
            .catch(() => {
            })
        })
      },
    checkEventById (id) {
        return api().get(`/calendar/${id}`)
    },
}
