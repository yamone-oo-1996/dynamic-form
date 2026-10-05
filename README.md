# Dynamic Form Management

<!-- Demo change: no functional impact, used to exercise the Mergify PR workflow. -->


## Contract processing status

`GET /dynamic_form/api/v1/service/contracts/status?ref_id=123&ref_type=CONTRACT`

Supply the configured client credential in the `X-FRONTIIR-CLIENT` header.
Both query parameters are required strings. `ref_type` accepts `CONTRACT` or
`LAN_VOUCHER` (case-sensitive); for LAN vouchers, use the voucher ID as `ref_id`.

Example response:

```json
{
  "status": 200,
  "data": {
    "ref_id": "123",
    "ref_type": "CONTRACT",
    "is_processed": 1
  },
  "error": { "message": "" }
}
```

`is_processed` is the existing tracking flag: `0` means unprocessed or marked
for retry, and `1` means the HTML was dispatched to the PDF-generation service.
It does not confirm that the PDF is ready. Customer details and signatures are
not returned. This lookup does not modify tracking data or trigger processing.

Missing or malformed query parameters return `403`, following the existing
API convention. Unknown reference types or missing records return `404`.
Invalid client credentials return `401`.
