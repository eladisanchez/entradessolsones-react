import { useState } from "react";
import { Link } from "@inertiajs/react";
import { Flex, Button, Heading } from "@/components/atoms";
import { Card } from "@/components/molecules";
import { dayFormatted, ymd } from "@/utils/date";

const TicketTable = ({
  step = 2,
  productSlug,
  tickets,
  selectedDay,
  selectedHour,
}) => {
  const [showMore, setShowMore] = useState(false);

  return (
    <>
      {/* <Card> */}
      {/* {!selectedDay ? (
        <Flex spacerBottom={2}>
          <Heading tag="h3" size={3}>
            Properes sessions ({tickets.length})
          </Heading>
        </Flex>
      ) : (
        <Flex
          spacerBottom={2}
          justifyContent="space-between"
          alignItems="flex-end"
        >
          <Heading tag="h3" size={3}>
            <strong>{step}.</strong> Tria una sessió
          </Heading>
        </Flex>
      )} */}

      <Flex gap={1} flexDirection="column">
        {tickets.map(
          (ticket, i) =>
            (showMore || i < 5) && (
              <Button
                href={`/activitat/${productSlug}/${ticket.day}/${ticket.hour}`}
                // outline={
                //   !(
                //     ymd(selectedDay) == ticket.day &&
                //     selectedHour == ticket.hour
                //   )
                // }
                preserveScroll
                only={['day', 'hour']}
                color={
                  !(
                    ymd(selectedDay) == ticket.day &&
                    selectedHour == ticket.hour
                  )
                    ? "white"
                    : "primary"
                }
                block="true"
              >
                <strong>{dayFormatted(ticket.day)}</strong> {ticket.hour}
              </Button>
            )
        )}
      </Flex>
      {!showMore && tickets.length > 5 && (
        <Button link block onClick={() => setShowMore(true)}>
          Mostra totes les dates
        </Button>
      )}
      {/* {selectedDay && (
        <Button link block href={`/activitat/${productSlug}`} preserveScroll>
          Mostra les properes sessions
        </Button>
      )} */}

      {/* </Card> */}
    </>
  );
};

export default TicketTable;
