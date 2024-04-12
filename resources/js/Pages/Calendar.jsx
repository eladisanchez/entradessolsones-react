import React, { Suspense } from "react";
import { Head } from "@inertiajs/react";
import { HomeNav } from "@/components";
import { Container } from "@/components/ui";

const FullCalendar = React.lazy(() => import("@/components/Calendar/Calendar"));

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
