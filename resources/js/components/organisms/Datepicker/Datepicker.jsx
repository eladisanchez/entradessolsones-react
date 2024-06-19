import { useState } from "react";
import { Calendar as PrimeCalendar } from "primereact/calendar";
import { Heading, Flex, Button } from "@/components/atoms";
import { Card } from "@/components/molecules";
import styles from "./Datepicker.module.scss";
import { dayFormatted } from "@/utils/date";

const Datepicker = ({ step = 1, availableDays, selectedDay, onSelectDay }) => {
  const [viewDate, setViewDate] = useState(new Date());

  const enabledDates = availableDays.map((date) => {
    return new Date(date);
  });

  const availableMonths = Array.from(
    new Set(enabledDates.map((date) => date.getMonth()))
  );

  return (
    <Card className={styles.calendar}>
      {/* <Flex spacerBottom={2}>
        <Heading tag="h3" size={3}>
          <span className={styles.step}>{step}.</span> Tria un dia
        </Heading>
      </Flex> */}
      {selectedDay ? (
        <Flex justifyContent="space-between">
          <Heading tag="h3" size={3}>
            {dayFormatted(selectedDay)}
          </Heading>
          <Button
            onClick={() => onSelectDay(null)}
            variant="link"
            color="primary"
          >
            X
          </Button>
        </Flex>
      ) : (
        <PrimeCalendar
          inline
          enabledDates={enabledDates}
          minDate={enabledDates[0]}
          maxDate={enabledDates[enabledDates.length - 1]}
          onChange={onSelectDay}
          showOtherMonths={false}
          value={selectedDay ?? enabledDates[0]}
          view="date"
          viewDate={viewDate}
          onViewDateChange={(e) => {
            if (availableMonths.includes(e.value.getMonth())) {
              setViewDate(e.value);
            }
          }}
        />
      )}
    </Card>
  );
};

export default Datepicker;
