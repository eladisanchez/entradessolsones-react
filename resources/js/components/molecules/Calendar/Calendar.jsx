import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import { Card } from "@/components/atoms";

const Calendar = ({ events }) => {
  const start = new Date();
  const end = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
  return (
    <Card spacerTop={3}><FullCalendar
      plugins={[dayGridPlugin]}
      initialView="dayGridMonth"
      events={events}
      locale="ca"
      firstDay={1}
      hiddenDays={[1, 2]}
      validRange={{
        start,
        end,
      }}
      eventClick={(info) => {
        info.jsEvent.preventDefault();
        if (info.event.url) {
          window.open(info.event.url);
        }
      }}
      buttonText={{
        today: "avui",
        month: "Mes",
        week: "Setmana",
        day: "Dia",
        list: "llista",
      }}
    /></Card>
  );
};

export default Calendar;
