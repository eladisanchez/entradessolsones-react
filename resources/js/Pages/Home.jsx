import { Head } from "@inertiajs/react";
import { HomeNav } from "@/components";
import { Heading, Container, Thumbnail, Grid } from "@/components/ui";

export default function Product({ products }) {
  const types = {
    activities: "activitats",
    events: "esdeveniments",
  };
  return (
    <>
      <Head title="Entrades Solsonès" />
      <Container>
        <HomeNav />
        {Object.keys(types).map((type) => (
          <div id={types[type]} key={type} style={{scrollPaddingTop: '100px'}}>
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
    </>
  );
}
