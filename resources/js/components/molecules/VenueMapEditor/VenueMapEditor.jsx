import { useState } from "react";
import { usePage } from "@inertiajs/react";
import styles from "./VenueMapEditor.module.scss";
import classNames from "classnames";
import { Flex, Input, Button } from "@/components/atoms";

function VenueMapEditor({ id, seats, title }) {
  const [map, setMap] = useState(JSON.parse(seats));
  const [mouseDown, setMouseDown] = useState(false);
  const [erase, setErase] = useState(false);
  const [seat, setSeat] = useState(1);
  const [row, setRow] = useState(1);
  const { csrf_token } =  usePage().props;
  const { message, setMessage} = useState(null)

  const gridItems = [];
  for (let y = 1; y <= 45; y++) {
    for (let x = 1; x <= 45; x++) {
      gridItems.push({ x, y });
    }
  }

  const isSeat = (square) => {
    return map.find(function (b) {
      return b.x == square.x && b.y == square.y;
    });
  };

  const handleSelect = (square) => {
    const newMap = [...map];
    if (isSeat(square)) {
      const newMapFiltered = newMap.filter(function (s) {
        return s.x != square.x || s.y != square.y;
      });
      setMap(newMapFiltered);
    } else {
      const newSeat = seat + 1;
      setSeat(newSeat);
      newMap.push(square);
      setMap(newMap);
    }
  };

  const handleChangeRow = (e) => {
    setRow(e.target.value);
    setSeat(1);
  };

  const handleSave = async () => {
    const data = { seats: map, _token: csrf_token };
    const response = await fetch(`/admin/venues/${id}/edit/map`, {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });
    const cart = await response.json();
    if (cart.error) {
      alert('Error: '+cart.error);
    } else {
      setMessage('El plànol s\'ha guardat correctament.')
    }
  };

  return (
    <>
      <div className={styles.container}>
        <Flex gap={3}>
          <div>
            <div
              className={styles.map}
              style={{
                gridTemplateColumns: `repeat(45, 1fr)`,
              }}
            >
              {gridItems.map((square) => {
                const squareSeat = isSeat(square);
                return (
                  <div
                    key={square}
                    className={classNames(styles.seat, {
                      [styles.selected]: isSeat(square),
                    })}
                    style={{
                      gridRow: square.x,
                      gridColumn: square.y,
                    }}
                    onClick={() =>
                      handleSelect({
                        s: seat,
                        f: row,
                        x: square.x,
                        y: square.y,
                      })
                    }
                  >
                    {squareSeat && (
                      <>
                        <span>{squareSeat.f}</span>
                        <strong>{squareSeat.s}</strong>
                      </>
                    )}
                  </div>
                );
              })}
            </div>
          </div>
          <div style={{ width: "300px" }}>
            <h2>{title}</h2>
            <Flex gap={3} style={{paddingTop:'24px'}}>
              <Input
                label="Fila"
                type="number"
                value={row}
                onChange={handleChangeRow}
              />
              <Input
                type="number"
                value={seat}
                label="Seient"
                onChange={(e) => setSeat(e.target.value)}
              />
            </Flex>
            <Button onClick={handleSave}>Desa el plànol</Button>
          </div>
        </Flex>
      </div>
    </>
  );
}

export default VenueMapEditor;
