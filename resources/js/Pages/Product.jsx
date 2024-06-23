import React, { Suspense, useRef } from "react";
import { Head, router } from "@inertiajs/react";
import { useState } from "react";
import {
  Container,
  Heading,
  Grid,
  Spacer,
  Flex,
  TextFormat,
  Tab,
  Icon,
} from "@/components/atoms";
import { Card } from "@/components/molecules";
import { TicketTable } from "@/components/organisms";
import { useCart } from "@/contexts/CartContext";
import styles from "./Product.module.scss";
import { ymd } from "@/utils/date";
import { Link } from "@inertiajs/react";

const Datepicker = React.lazy(() =>
  import("@/components/organisms/Datepicker/Datepicker")
);
const VenueMap = React.lazy(() =>
  import("@/components/organisms/VenueMap/VenueMap")
);
const RateSelect = React.lazy(() =>
  import("@/components/organisms/RateSelect/RateSelect")
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

  const [selectedRate, setSelectedRate] = useState(false);

  const [ticketView, setTicketView] = useState(
    availableDays.length == 1 ? "list" : "calendar"
  );

  const { addToCart } = useCart();
  const ticketSectionRef = useRef(null);

  const ticketsByDay = (day) => {
    const queryDay = day ?? selectedDay;
    if (queryDay) {
      console.log(queryDay);
      const formattedDay = ymd(selectedDay);
      return tickets.filter((ticket) => {
        return ticket.day == formattedDay;
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
    const visitOptions = {
      method: "get",
      replace: false,
      preserveState: true,
      preserveScroll: true,
      only: ["day", "hour"],
    };
    if (!e) {
      setSelectedDay(null);
      router.visit(`/activitat/${product.name}`, visitOptions);
      return;
    }
    setSelectedDay(e.value);
    if (ticketsByDay(e.value).length == 1) {
      router.visit(
        `/activitat/${product.name}/${ymd(e.value)}/${ticketsByDay()[0].hour}`,
        visitOptions
      );
      return;
    }
    router.visit(`/activitat/${product.name}/${ymd(e.value)}`, visitOptions);
  };

  const handleCloseRate = (e) => {
    router.visit(`/activitat/${product.name}/${day}`, {
      method: "get",
      replace: false,
      preserveState: true,
      preserveScroll: true,
      only: ["day", "hour"],
    });
  };

  const handleSelectRate = async (data) => {
    if (!!product.venue_id) {
      setSelectedRate(data);
    } else {
      handleAddToCart(data);
    }
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
      <Head>
        <title>{`${product.title}  -  Entrades Solsonès`}</title>
        <meta name="description" content={product.summary} />
      </Head>

      <div className={styles.productContent}>
        <div
          className={styles.productHeader}
          style={{
            backgroundImage: "url('/image/" + product.image + "')",
          }}
        ></div>
        <Container className={styles.productContainer}>
          <Spacer className={styles.organizer}>
            <Link href={"/#" + product.target}>{targets[product.target]}</Link>{" "}
            <span>&#x203A;</span> {product.organizer.username}
          </Spacer>
          <Heading tag="h1" color="light" spacerTop={0} spacerBottom={6}>
            {product.title}
          </Heading>
          <Grid columns={2}>
            <section>
              {/* <Spacer bottom={3}>
                <Flex gap={2}>
                  <Button block={true} color="white" outline={true}>
                    Descripció
                  </Button>
                  <Button block={true} color="white" outline={true}>
                    Detalls
                  </Button>
                </Flex>
              </Spacer> */}
              <Card>
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
              </Card>
            </section>
            <aside>
              {/* <Spacer bottom={3}>
                <Button
                  block={true}
                  size="lg"
                  onClick={() =>
                    ticketSectionRef.current?.scrollIntoView({
                      behavior: "smooth",
                    })
                  }
                >
                  Compra entrades
                </Button>
              </Spacer> */}
              <Spacer bottom={3}>
                <img
                  src={"/image/" + product.image}
                  alt={product.title}
                  className={styles.thumbnail}
                />
              </Spacer>
              <Spacer bottom={3}>
                {availableDays.length > 0 ? (
                  <Suspense fallback={<div>Carregant...</div>}>
                    <Flex>
                      <Tab
                        selected={ticketView == "calendar"}
                        onClick={() => setTicketView("calendar")}
                      >
                        <Flex gap="1">
                          <Icon icon="calendar" />
                          <span>Calendari</span>
                        </Flex>
                      </Tab>
                      <Tab
                        selected={ticketView == "list"}
                        onClick={() => setTicketView("list")}
                      >
                        <Flex gap="1">
                          <Icon icon="list" />
                          <span>Properes sessions</span>
                        </Flex>
                      </Tab>
                    </Flex>
                    <Card hasTabs={true}>
                      {ticketView == "calendar" && (
                        <Datepicker
                          availableDays={availableDays}
                          onSelectDay={handleSelectDay}
                          selectedDay={selectedDay}
                        />
                      )}
                      {ticketView == "list" && (
                        <TicketTable
                          selectedDay={selectedDay}
                          selectedHour={hour}
                          productSlug={product.name}
                          tickets={ticketsByDay()}
                        ></TicketTable>
                      )}
                    </Card>
                  </Suspense>
                ) : (
                  <Card>
                    <TextFormat color="faded" textAlign="center">
                      Actualment no hi ha dates disponibles per aquesta
                      activitat.
                    </TextFormat>
                  </Card>
                )}
              </Spacer>
              {availableDays.length > 0 && (
                <Spacer bottom={3}>
                  <TicketTable
                    selectedDay={selectedDay}
                    selectedHour={hour}
                    productSlug={product.name}
                    tickets={ticketsByDay()}
                  ></TicketTable>
                </Spacer>
              )}
              {hour && (
                <Spacer bottom={3}>
                  <Suspense fallback={<div>Carregant...</div>}>
                    <RateSelect
                      rates={rates}
                      minQty={product.min_tickets}
                      maxQty={product.max_tickets}
                      selectRate={handleSelectRate}
                      close={handleCloseRate}
                    />
                  </Suspense>
                </Spacer>
              )}
              {selectedRate && (
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
                            r: selectedRate.id,
                          },
                        ],
                      })
                    }
                  />
                </Suspense>
              )}
            </aside>
          </Grid>

          {/* <section
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
                  selectedHour={hour}
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
          </section> */}
        </Container>
      </div>
    </>
  );
}
