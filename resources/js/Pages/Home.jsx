import { useState } from "react";
import { Head } from "@inertiajs/react";
import { HomeNav } from "@/components/organisms";
import { Heading, Container, Grid, Spacer } from "@/components/atoms";
import { Thumbnail } from "@/components/molecules";
import { Waypoint } from "react-waypoint";

const Home = ({ products }) => {
  const [activeSection, setActiveSection] = useState("");

  const types = {
    activities: "activitats",
    events: "esdeveniments",
  };

  const handleWaypointEnter = (section) => {
    setActiveSection(section);
  };
  return (
    <>
      <Head>
        <title>Entrades Solsonès</title>
        <meta
          name="description"
          content="Portal de venda d'entrades de Turisme Solsonès. Visites guiades, teatre, esdeveniments i experiències singulars a la comarca del Solsonès. "
        />
      </Head>
      <Container>
        <HomeNav activeSection={activeSection} />
        {Object.keys(types).map((type) => (
          <div
            id={types[type]}
            key={type}
            style={{ scrollPaddingTop: "100px" }}
          >
            <Waypoint onEnter={() => handleWaypointEnter(type)} />
            {products[type].map((category) => (
              <section className="category">
                <Heading
                  tag="h2"
                  size={2}
                  center={true}
                  spacerTop={6}
                  spacerBottom={6}
                >
                  {category.title}
                </Heading>
                <Grid>
                  {category.products.map((product) => (
                    <Thumbnail key={product.id} product={product} />
                  ))}
                </Grid>
              </section>
            ))}
          </div>
        ))}
      </Container>
      <Spacer size={8} />
    </>
  );
};

export default Home;
