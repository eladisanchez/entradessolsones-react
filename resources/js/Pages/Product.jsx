import React, { Suspense, useRef } from "react";
import { Head, Link, router } from "@inertiajs/react";
import { useState } from "react";
import { Container, Heading, Grid, Card, Button, TicketTable } from "@/components/ui";
import { useCart } from "@/contexts/CartContext";
import styles from "./Product.module.scss";
import { ymd } from "@/utils/date";

const Datepicker = React.lazy(() =>
  import("@/components/ui/Datepicker/Datepicker")
);
const VenueMap = React.lazy(() => import("@/components/VenueMap/VenueMap"));
const RateModal = React.lazy(() => import("@/components/RateModal/RateModal"));

export default function Product({
  product,
  tickets,
  availableDays,
  rates,
  day,
  hour,
}) {
  const [selectedDay, setSelectedDay] = useState(day ? new Date(day) : null);

  const { addToCart } = useCart();
  const ticketSectionRef = useRef(null);

  const ticketsByDay = () => {
    if (selectedDay) {
      const day = ymd(selectedDay);
      return tickets.filter((ticket) => {
        return ticket.day == day;
      });
    }
    return tickets;
  };

  const currentTicket =
    tickets.filter((ticket) => {
      return ticket.day == day && ticket.hour == hour;
    })[0] ?? null;

  const seats = currentTicket && JSON.parse(currentTicket.seats);

  const handleSelectDay = (e) => {
    setSelectedDay(e.value);
    router.visit(`/activitat/${product.name}/${ymd(e.value)}`, {
      method: "get",
      replace: false,
      preserveState: true,
      preserveScroll: true,
    });
  };

  const handleCloseRate = (e) => {
    router.visit(`/activitat/${product.name}/${day}`, {
      method: "get",
      replace: false,
      preserveState: true,
      preserveScroll: true,
    });
  };

  const handleAddToCart = async (data) => {
    const dataToCart = {
      ...data,
      product_id: product.id,
      day,
      hour,
    };
    addToCart(dataToCart);
  };

  return (
    <>
      <Head title={product.title} />
      <div className={styles.productContainer}>
        <div
          className={styles.productHeader}
          style={{
            backgroundImage:
              'url("https://source.unsplash.com/random/1000x400")',
          }}
        ></div>
        <Container style={{ position: "relative", zIndex: 1 }}>
          <p className={styles.organizer}>{product.organizer.username}</p>
          <Heading tag="h1" color="light" spacerTop={0}>
            {product.title}
          </Heading>

          <Grid columns={2}>
            <div>
              <Button
                size="lg"
                block={true}
                onClick={() =>
                  ticketSectionRef.current?.scrollIntoView({
                    behavior: "smooth",
                  })
                }
              >
                Compra entrades
              </Button>
            </div>

            <Card>
              <div className={styles.productInfo}>
                <div
                  dangerouslySetInnerHTML={{
                    __html: product.description,
                  }}
                ></div>
                <div
                  className={styles.col}
                  dangerouslySetInnerHTML={{
                    __html: product.schedule,
                  }}
                ></div>
              </div>
            </Card>
          </Grid>

          <section
            id="tickets"
            className={styles.tickets}
            ref={ticketSectionRef}
          >
            <Grid columns={2}>
              <section>
                <h3>Calendari</h3>
                <Suspense fallback={<div>Carregant...</div>}>
                  <Datepicker
                    availableDays={availableDays}
                    onSelectDay={handleSelectDay}
                    selectedDay={selectedDay}
                  />
                </Suspense>
              </section>

              <section>
                <TicketTable
                  selectedDay={selectedDay}
                  productSlug={product.name}
                  tickets={ticketsByDay()}
                ></TicketTable>
              </section>
              
              {hour && (
                <RateModal
                  rates={rates}
                  minQty={product.min_tickets}
                  maxQty={product.max_tickets}
                  addToCart={handleAddToCart}
                  close={handleCloseRate}
                />
              )}
            </Grid>

            {!!product.venue_id && hour && (
              <div>
                {ticketsByDay().map((ticket) => (
                  <p>
                    <Link
                      href={`/activitat/${product.name}/${ticket.day}/${ticket.hour}`}
                      preserveState
                      preserveScroll
                    >
                      {ticket.day} - {ticket.hour}
                    </Link>
                  </p>
                ))}
                {!!seats && (
                  <Suspense fallback={<div>Carregant...</div>}>
                    <VenueMap
                      seats={seats}
                      bookedSeats={[]}
                      addToCart={(seat) =>
                        handleAddToCart({
                          qty: [1],
                          seats: [
                            {
                              s: seat.s,
                              f: seat.f,
                              z: seat.z,
                              r: 10,
                            },
                          ],
                        })
                      }
                    />
                  </Suspense>
                )}
              </div>
            )}
          </section>
        </Container>
      </div>
    </>
  );
}
