var calendarEl = document.getElementById('calendar');
var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: [ 'dayGrid', 'googleCalendar' ],
    googleCalendarApiKey: "AIzaSyD2_0dhaWwK4IhHtYY2a4mKrSFAFfa3L-4",
    events: {
        googleCalendarId: 'avalon.tfg@gmail.com'
    },
    eventClick: function(info) {
        info.jsEvent.preventDefault(); // don't let the browser navigate
    
        if (info.event.url) {
            //do nothing
        }
      }
});
calendar.render();