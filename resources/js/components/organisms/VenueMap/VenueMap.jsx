import { TransformWrapper, TransformComponent } from "react-zoom-pan-pinch";
import { useState } from "react";
import { useCart } from "@/contexts/CartContext";
import styles from "./VenueMap.module.scss";
import classNames from "classnames";

const VenueMap = ({ seats, bookedSeats, cartSeats, selectedSeats, setSelectedSeats, addToCart }) => {
  const { cart } = useCart();
  const seatsX = seats.map((o) => {
    return o.x;
  });
  const seatsY = seats.map((o) => {
    return o.y;
  });
  const minX = Math.min.apply(this, seatsX);
  const maxY = Math.max.apply(this, seatsY);
  const minY = Math.min.apply(this, seatsY);
  const cols = maxY - minY;

  const booked = (seat) => {
    return bookedSeats.find(function (b) {
      return b.s == seat.s && b.f == seat.f;
    });
  };
  const selected = (seat) => {
    return selectedSeats.find(function (b) {
      return b.s == seat.s && b.f == seat.f;
    });
  };

  const handleSelect = (seat) => {
    if (booked(seat)) {
      return false;
    }
    const newSelected = [...selectedSeats];
    if (selected(seat)) {
      const newSelectedFiltered = newSelected.filter(function (s) {
        return s.f != seat.f && s.s != seat.s;
      });
      setSelectedSeats(newSelectedFiltered);
    } else {
      // addToCart(seat);
      newSelected.push(seat);
      setSelectedSeats(newSelected);
    }
  };

  return (
    <TransformWrapper>
      {({ zoomIn, zoomOut, resetTransform, ...rest }) => (
        <>
          <div className={styles.tools}>
            <button onClick={() => zoomIn()} aria-label="Amplia">+</button>
            <button onClick={() => zoomOut()} aria-label="Redueix">-</button>
            <button onClick={() => resetTransform()} aria-label="Torna a la mida original">x</button>
          </div>
          <TransformComponent>
            <div
              className={styles.map}
              style={{
                gridTemplateColumns: `repeat(${cols + 1}, 1fr)`,
              }}
            >
              {seats.map((seat) => (
                <div
                  key={`${seat.f}-${seat.s}`}
                  className={classNames(styles.seat, {
                    [styles.booked]: booked(seat),
                    [styles.selected]: selected(seat),
                  })}
                  style={{
                    gridRow: seat.x - minX + 1,
                    gridColumn: seat.y - minY + 1,
                  }}
                  onClick={() => handleSelect(seat)}
                ></div>
              ))}
            </div>
          </TransformComponent>
        </>
      )}
    </TransformWrapper>
  );
}

export default VenueMap;
