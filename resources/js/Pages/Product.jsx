import React, { Suspense, useRef } from "react";
import { Head, router } from "@inertiajs/react";
import { useState } from "react";
import { Container, Heading, Grid, Card, Button } from "@/components/atoms";
import { TicketTable } from "@/components/molecules";
import { useCart } from "@/contexts/CartContext";
import styles from "./Product.module.scss";
import { ymd } from "@/utils/date";
import { Link } from "@inertiajs/react";

const Datepicker = React.lazy(() =>
  import("@/components/molecules/Datepicker/Datepicker")
);
const VenueMap = React.lazy(() =>
  import("@/components/molecules/VenueMap/VenueMap")
);
const RateSelect = React.lazy(() =>
  import("@/components/molecules/RateSelect/RateSelect")
);

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

  const targets = {
    individual: "Activitats turístiques",
    esdeveniments: "Teatre, concerts i esdeveniments",
    altres: "Altres activitats",
  };

  return (
    <>
      <Head title={product.title} />
      <div className={styles.productContainer}>
        <div
          className={styles.productHeader}
          style={{
            backgroundImage: "url('/image/" + product.image + "')",
          }}
        ></div>
        <Container style={{ position: "relative", zIndex: 1 }}>
          <p className={styles.organizer}>
            <Link href={"/#" + product.target}>{targets[product.target]}</Link>{" "}
            <span>&#x203A;</span> {product.organizer.username}
          </p>
          <Heading tag="h1" color="light" spacerTop={0} spacerBottom={4}>
            {product.title}
          </Heading>

          <Grid columns={2}>
            <div>
              <p>
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
              </p>
              <p>
                <img
                  src={"/image/" + product.image}
                  alt={product.title}
                  className={styles.thumbnail}
                />
              </p>
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
            <Grid columns={3}>
              <section>
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

              <section>
                {hour && (
                  <RateSelect
                    rates={rates}
                    minQty={product.min_tickets}
                    maxQty={product.max_tickets}
                    addToCart={handleAddToCart}
                    close={handleCloseRate}
                  />
                )}
              </section>
            </Grid>

            {!!product.venue_id && hour && (
              <div>
                <TicketTable
                  productSlug={product.name}
                  tickets={ticketsByDay()}
                ></TicketTable>
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
