import { useState } from "react";
import { Link } from "@inertiajs/react";
import { Card, Flex, Button, Heading } from "@/components/atoms";
import { dayFormatted } from "@/utils/date";
import styles from "./TicketTable.module.scss";

const TicketTable = ({ productSlug, tickets, selectedDay }) => {
  const [showMore, setShowMore] = useState(false);
  return (
    <div>
      {!selectedDay ? (
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
            Sessions per al {dayFormatted(selectedDay)}
          </Heading>
          <Link href={`/activitat/${productSlug}`} preserveScroll>
            Torna
          </Link>
        </Flex>
      )}
      <Card spacerBottom={2}>
        <table className={styles.ticketTable}>
          {tickets.map(
            (ticket, i) =>
              (showMore || i < 5) && (
                <tr>
                  <td>
                    <strong>{dayFormatted(ticket.day)}</strong> {ticket.hour}
                  </td>
                  <td>
                    <Button
                      href={`/activitat/${productSlug}/${ticket.day}/${ticket.hour}`}
                    >
                      Compra entrades
                    </Button>
                  </td>
                </tr>
              )
          )}
        </table>
      </Card>
      {!showMore && tickets.length > 5 && (
        <Button link onClick={() => setShowMore(true)}>
          Mostra totes les dates
        </Button>
      )}
    </div>
  );
};

export default TicketTable;
