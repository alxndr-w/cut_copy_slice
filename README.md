# Cut & Copy für REDAXO Slices - WIP

Bloecks-Fork, der die Copy-Paste-Slice-Funktion auf moderne Füße stellen soll.

## User permissions

Users must either be administrators or have assigned the permission `cut_copy_slice[cutncopy]` (»Copy blocks«) to change the status of a block.

## Extension Points

| EP                      | Description                      |
|-------------------------|----------------------------------|
| `SLICE_COPIED`          | Is called after a block has been copied to the clipboard |
| `SLICE_CUT`             | Is called after a block has been copied to the clipboard to be cut from the current article |
| `SLICE_INSERTED`        | Is called after a block has been pasted into the current article |
