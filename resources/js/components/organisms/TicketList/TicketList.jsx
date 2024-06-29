import { Flex, Button, Icon } from "@/components/atoms";
import { ymd } from "@/utils/date";

const TicketList = ({ productSlug, tickets, selectedDay, selectedHour }) => {
  return (
    <>
      <Flex gap={1} alignItems="center">
        <Icon icon="hour" />
        <span>Tria una sessió:</span>
        {tickets.map((ticket, i) => (
          <Button
            href={`/activitat/${productSlug}/${ticket.day}/${ticket.hour}`}
            size="sm"
            outline={
              !(ymd(selectedDay) == ticket.day && selectedHour == ticket.hour)
            }
            preserveScroll
            preserveState
            only={["day", "hour"]}
          >
            {ticket.hour}
          </Button>
        ))}
      </Flex>
    </>
  );
};

export default TicketList;
