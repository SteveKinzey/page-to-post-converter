#!/usr/bin/env python3
"""
api_request_template.py: Starter template for interacting with REST APIs in Python.

Demonstrates:
  - Sending GET and POST requests with the requests library
  - Handling headers, query parameters, and JSON payloads
  - Checking response status and basic error handling
  - Parsing JSON responses into Python dict/list
"""

import requests
import sys

# Default headers – include a User-Agent to avoid 403 errors
DEFAULT_HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Python API Client)',
    'Accept': 'application/json'
}


def get_json(url, params=None, headers=None, timeout=10):
    """
    Send a GET request to the given URL and return parsed JSON.
    params: dict of query parameters
    headers: dict of HTTP headers
    timeout: seconds before giving up
    """
    hdrs = headers or DEFAULT_HEADERS
    try:
        resp = requests.get(url, params=params, headers=hdrs, timeout=timeout)
        resp.raise_for_status()
    except requests.RequestException as e:
        print(f"GET request failed: {e}", file=sys.stderr)
        return None

    # Parse JSON body
    try:
        return resp.json()
    except ValueError:
        print("Failed to parse JSON response", file=sys.stderr)
        return None


def post_json(url, payload=None, headers=None, timeout=10):
    """
    Send a POST request with a JSON payload and return parsed JSON.
    payload: dict to send as JSON body
    headers: dict of HTTP headers
    timeout: seconds before giving up
    """
    hdrs = headers or DEFAULT_HEADERS.copy()
    hdrs['Content-Type'] = 'application/json'
    try:
        resp = requests.post(url, json=payload, headers=hdrs, timeout=timeout)
        resp.raise_for_status()
    except requests.RequestException as e:
        print(f"POST request failed: {e}", file=sys.stderr)
        return None

    try:
        return resp.json()
    except ValueError:
        print("Failed to parse JSON response", file=sys.stderr)
        return None


def main():
    # Example usage flow
    url = input("Enter API URL: ").strip()
    method = input("GET or POST? ").strip().upper()

    if method == 'GET':
        # Optional: prompt for query params
        resp = get_json(url)
    elif method == 'POST':
        # Example payload prompt
        payload = input("Enter JSON payload (or leave blank): ")
        try:
            data = payload and requests.utils.json.loads(payload)
        except Exception:
            print("Invalid JSON payload", file=sys.stderr)
            return
        resp = post_json(url, payload=data)
    else:
        print("Unsupported method. Use GET or POST.")
        return

    if resp is not None:
        # Pretty-print the JSON response
        print("Response JSON:")
        print(requests.utils.json.dumps(resp, indent=2))


if __name__ == '__main__':
    main()
