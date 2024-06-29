import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import { Card } from "@/components/molecules";
import { useState, useEffect } from "react";

const Calendar = ({ events }) => {
  const start = new Date();
  const end = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
  const [initialView, setInitialView] = useState('dayGridMonth');

  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth < 768) {
        setInitialView('timeGridDay'); // Vista diària per mòbil
      } else {
        setInitialView('dayGridMonth'); // Vista mensual per sobretaula
      }
    };

    handleResize(); // Inicialment quan es carrega la pàgina
    window.addEventListener('resize', handleResize); // Quan es redimensiona la finestra

    return () => {
      window.removeEventListener('resize', handleResize);
    };
  }, []);

  return (
    <Card spacerTop={3}><FullCalendar
      plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin]}
      initialView={initialView}
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
