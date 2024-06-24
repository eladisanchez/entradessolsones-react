import { useState } from "react";
import { Flex, Button } from "@/components/atoms";
import { dayFormatted, ymd } from "@/utils/date";

const TicketTable = ({ productSlug, tickets, selectedDay, selectedHour }) => {
  const [showMore, setShowMore] = useState(false);

  return (
    <>
      <Flex gap={1} flexDirection="column">
        {tickets.map(
          (ticket, i) =>
            (showMore || i < 5) && (
              <Button
                href={`/activitat/${productSlug}/${ticket.day}/${ticket.hour}`}
                outline={
                  !(
                    ymd(selectedDay) == ticket.day &&
                    selectedHour == ticket.hour
                  )
                }
                preserveScroll
                preserveState
                only={["day", "hour"]}
                block="true"
              >
                <strong>{dayFormatted(ticket.day)}</strong> {ticket.hour}
              </Button>
            )
        )}
        {!showMore && tickets.length > 5 && (
          <Button link block onClick={() => setShowMore(true)}>
            Mostra totes les dates
          </Button>
        )}
      </Flex>
    </>
  );
};

export default TicketTable;
