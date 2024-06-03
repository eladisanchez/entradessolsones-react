import React, { Suspense } from "react";
import { Head } from "@inertiajs/react";
import { HomeNav } from "@/components/organisms";
import { Container } from "@/components/atoms";

const FullCalendar = React.lazy(() => import("@/components/molecules/Calendar"));

export default function Calendar({ events }) {
  return (
    <>
      <Head title="Calendari" />
      <HomeNav view="calendar" />
      <Container>
        <Suspense fallback={<div>Carregant...</div>}>
          <FullCalendar
            events={events}
          />
        </Suspense>
      </Container>
    </>
  );
}
