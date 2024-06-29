import React, { Suspense, useRef, useEffect } from "react";
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
  Button,
} from "@/components/atoms";
import { Card, Modal } from "@/components/molecules";
import { TicketTable, TicketList } from "@/components/organisms";
import { useCart } from "@/contexts/CartContext";
import styles from "./Product.module.scss";
import { ymd, dayFormatted } from "@/utils/date";
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

  const [selectedSeats, setSelectedSeats] = useState([]);

  const [ticketView, setTicketView] = useState(
    availableDays.length == 1 ? "list" : "calendar"
  );

  const { addToCart, toggleCart } = useCart();
  const daySectionRef = useRef(null);
  const ticketSectionRef = useRef(null);

  const ticketsByDay = (day) => {
    const queryDay = day ?? selectedDay;
    if (queryDay) {
      const formattedDay = ymd(queryDay);
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

  const seats = currentTicket && currentTicket.seats;

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
    const tickets = ticketsByDay(e.value);
    if (tickets.length == 1) {
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
    const dataToCart = !!product.venue_id
      ? {
          qty: selectedSeats.count,
          seats: selectedSeats,
          rate: selectedRate,
          product_id: product.id,
          day,
          hour,
        }
      : {
          ...data,
          product_id: product.id,
          day,
          hour,
        };
    addToCart(dataToCart);
    setSelectedRate(null);
    setSelectedSeats([]);
    toggleCart();
  };

  const targets = {
    individual: "Activitats turístiques",
    esdeveniments: "Teatre, concerts i esdeveniments",
    altres: "Altres activitats",
  };

  useEffect(() => {
    if (day) {
      if (hour) {
        if (ticketSectionRef.current) {
          ticketSectionRef.current.scrollIntoView({ behavior: "smooth" });
        }
      } else {
        if (daySectionRef.current) {
          daySectionRef.current.scrollIntoView({ behavior: "smooth" });
        }
      }
    }
  }, [ticketSectionRef.current, day, hour, daySectionRef.current]);

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
            backgroundImage: `url('${
              product.image
                ? "/image/" + product.image
                : "/assets/img/placeholder.png"
            }')`,
          }}
        ></div>
        <Container className={styles.productContainer}>
          <Spacer className={styles.organizer}>
            <Link href="/">Inici</Link>
            <span>&#x203A;</span>
            <Link href={"/#" + product.target}>
              {targets[product.target]}
            </Link>{" "}
            <span>&#x203A;</span> {product.organizer.username}
          </Spacer>
          <Heading tag="h1" color="light" spacerTop={0} spacerBottom={6}>
            {product.title}
          </Heading>
          <Grid columns={2}>
            <section>
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
                  src={
                    product.image
                      ? "/image/" + product.image
                      : "/assets/img/placeholder.png"
                  }
                  alt={product.title}
                  className={styles.thumbnail}
                />
              </Spacer>
              <div ref={daySectionRef}>
                {availableDays.length > 0 ? (
                  availableDays.length > 1 && (
                    <Spacer bottom={3}>
                      <Suspense fallback={<div>Carregant...</div>}>
                        <Flex>
                          <Tab
                            selected={ticketView == "calendar"}
                            onClick={() => setTicketView("calendar")}
                          >
                            <Flex gap="1" alignItems="center">
                              <Icon icon="calendar" />
                              <span>Calendari</span>
                            </Flex>
                          </Tab>

                          <Tab
                            selected={ticketView == "list"}
                            onClick={() => setTicketView("list")}
                          >
                            <Flex gap="1" alignItems="center">
                              <Icon icon="list" />
                              <span>Properes sessions</span>
                            </Flex>
                          </Tab>
                        </Flex>
                        <Card hasTabs={true}>
                          {ticketView == "calendar" && (
                            <>
                              <Datepicker
                                availableDays={availableDays}
                                onSelectDay={handleSelectDay}
                                selectedDay={selectedDay}
                              />
                              {selectedDay && ticketsByDay().length > 1 && (
                                <Spacer top={1}>
                                  <hr />
                                  <Spacer top={1}>
                                    <TicketList
                                      selectedDay={selectedDay}
                                      selectedHour={hour}
                                      productSlug={product.name}
                                      tickets={ticketsByDay()}
                                    />
                                  </Spacer>
                                </Spacer>
                              )}
                            </>
                          )}
                          {ticketView == "list" && (
                            <TicketTable
                              selectedDay={selectedDay}
                              selectedHour={hour}
                              productSlug={product.name}
                              tickets={tickets}
                            ></TicketTable>
                          )}
                        </Card>
                      </Suspense>
                    </Spacer>
                  )
                ) : (
                  <Card>
                    <TextFormat color="faded" textAlign="center">
                      Actualment no hi ha dates disponibles per aquesta
                      activitat.
                    </TextFormat>
                  </Card>
                )}
              </div>
              {hour && (
                <div ref={ticketSectionRef}>
                  <Spacer bottom={3}>
                    <Suspense fallback={<div>Carregant...</div>}>
                      <Card>
                        <Heading tag="h3" size={4} spacerBottom={3}>
                          Entrades per al {dayFormatted(selectedDay)} a les{" "}
                          {hour} h
                        </Heading>
                        <RateSelect
                          rates={rates}
                          minQty={product.min_tickets}
                          maxQty={product.max_tickets}
                          selectRate={handleSelectRate}
                          close={handleCloseRate}
                          venue={!!product.venue_id}
                        />
                      </Card>
                    </Suspense>
                  </Spacer>
                </div>
              )}
            </aside>
          </Grid>
        </Container>
      </div>
      {product.venue_id && seats && (
        <Modal
          width={700}
          isOpen={selectedRate}
          onClose={() => {
            setSelectedRate(null);
          }}
        >
          <Suspense fallback={<div>Carregant...</div>}>
            <Heading tag="h3" size={3} spacerBottom={2}>
              Tria les localitats
            </Heading>
            <VenueMap
              seats={seats}
              bookedSeats={[]}
              selectedSeats={selectedSeats}
              setSelectedSeats={setSelectedSeats}
            />
            <Spacer top={2}>
              <Flex justifyContent="flex-end" className={styles.venueTickets}>
                <Flex
                  spacerTop={3}
                  spacerBottom={3}
                  gap={3}
                  alignItems="flex-end"
                  justifyContent="space-between"
                >
                  <TextFormat color="faded">
                    {selectedSeats.length}{" "}
                    {selectedSeats.length == 1
                      ? "Entrada seleccionada"
                      : "Entrades seleccionades"}
                  </TextFormat>
                  <Button
                    block={true}
                    onClick={handleAddToCart}
                    size="lg"
                    disabled={!selectedSeats.length}
                  >
                    Afegeix al cistell
                  </Button>
                </Flex>
              </Flex>
            </Spacer>
          </Suspense>
        </Modal>
      )}
    </>
  );
}
