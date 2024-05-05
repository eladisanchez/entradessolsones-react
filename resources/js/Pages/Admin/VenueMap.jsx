import { Head } from "@inertiajs/react";
import VenueMapEditor from "@/components/molecules/VenueMapEditor";

function VenueMap({ venue }) {
  return (
    <>
      <Head title="Entrades Solsonès - Editor de plànol" />
      <VenueMapEditor seats={venue.seats} title={venue.name} id={venue.id} />
    </>
  );
}

VenueMap.layout = (page) => <>{page}</>;

export default VenueMap;
