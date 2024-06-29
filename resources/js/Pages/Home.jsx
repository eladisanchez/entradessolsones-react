import { useState } from "react";
import { Head } from "@inertiajs/react";
import { Heading, Container, Grid, Spacer } from "@/components/atoms";
import { Thumbnail, Featured } from "@/components/molecules";
import { Waypoint } from "react-waypoint";
import { HomeNav } from "@/components/organisms";

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
      <HomeNav activeSection={activeSection} />
      <Featured />
      {Object.keys(types).map((type) => (
        <div id={types[type]} key={type} style={{ scrollPaddingTop: "100px" }}>
          <Waypoint onEnter={() => handleWaypointEnter(type)} />
          {products &&
            products[type].map((category, i) => (
              <section className="category">
                <Spacer
                  top={6}
                  bottom={6}
                  background={i % 2 ? "light" : "white"}
                >
                  <Container>
                    <Heading
                      tag="h2"
                      size={2}
                      center={true}
                      spacerBottom={6}
                    >
                      {category.title}
                    </Heading>
                    <Grid>
                      {category.products.map((product) => (
                        <Thumbnail key={product.id} product={product} />
                      ))}
                    </Grid>
                  </Container>
                </Spacer>
              </section>
            ))}
        </div>
      ))}
    </>
  );
};

export default Home;
