import { useState } from "react";
import { Calendar as PrimeCalendar } from "primereact/calendar";
import { Heading, Flex } from "@/components/atoms";
import styles from "./Datepicker.module.scss";

const Datepicker = ({ step = 1, availableDays, selectedDay, onSelectDay }) => {
  const [viewDate, setViewDate] = useState(new Date());

  const enabledDates = availableDays.map((date) => {
    return new Date(date);
  });

  const availableMonths = Array.from(
    new Set(enabledDates.map((date) => date.getMonth()))
  );

  return (
    <div className={styles.calendar}>
      <Flex spacerBottom={2}>
        <Heading tag="h3" size={3}>
          <span className={styles.step}>{step}.</span> Tria un dia
        </Heading>
      </Flex>
      <PrimeCalendar
        inline
        enabledDates={enabledDates}
        minDate={enabledDates[0]}
        maxDate={enabledDates[enabledDates.length - 1]}
        onChange={onSelectDay}
        showOtherMonths={false}
        value={selectedDay??enabledDates[0]}
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
