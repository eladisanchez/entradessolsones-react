import { useState } from "react";
import { Link } from "@inertiajs/react";
import { Card, Flex } from "@/components/ui";
import { dayFormatted } from "@/utils/date";

const TicketTable = ({ productSlug, tickets, selectedDay }) => {
  const [showMore, setShowMore] = useState(false);
  return (
    <div>
      {!selectedDay ? (
        <Flex>
          <h3>Properes sessions ({tickets.length})</h3>
        </Flex>
      ) : (
        <Flex justifyContent="space-between" alignItems="flex-end">
          <h3>Sessions per al {dayFormatted(selectedDay)}</h3>
          <Link href={`/activitat/${productSlug}`} preserveScroll>
            Torna
          </Link>
        </Flex>
      )}
      <Card>
        {tickets.map(
          (ticket, i) =>
            (showMore || i < 5) && (
              <p>
                <Link
                  href={`/activitat/${productSlug}/${ticket.day}/${ticket.hour}`}
                  preserveState
                  preserveScroll
                >
                  {ticket.day} - {ticket.hour}
                </Link>
              </p>
            )
        )}
      </Card>
      {!showMore && tickets.length > 5 && (
        <button onClick={() => setShowMore(true)}>Mostra'n més</button>
      )}
    </div>
  );
};

export default TicketTable;
