import { useState } from "react";
import { Calendar as PrimeCalendar } from "primereact/calendar";
import styles from "./Datepicker.module.scss";

const Datepicker = ({ availableDays, selectedDay, onSelectDay }) => {
  const [viewDate, setViewDate] = useState(new Date());

  const enabledDates = availableDays.map((date) => {
    return new Date(date);
  });

  const availableMonths = Array.from(
    new Set(enabledDates.map((date) => date.getMonth()))
  );

  return (
    <div className={styles.calendar}>
      <PrimeCalendar
        inline
        enabledDates={enabledDates}
        minDate={enabledDates[0]}
        maxDate={enabledDates[enabledDates.length - 1]}
        onChange={onSelectDay}
        showOtherMonths={false}
        value={selectedDay}
        view="date"
        viewDate={viewDate}
        onViewDateChange={(e) => {
          if (availableMonths.includes(e.value.getMonth())) {
            setViewDate(e.value);
          }
        }}
      />
    </div>
  );
};

export default Datepicker;
