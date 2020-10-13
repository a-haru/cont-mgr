package main

import "contmgr/internal/services/cors"

func main() {
	a := cors.ValidationArgs{
		Id:  "test",
		Url: "http://example.com/",
	}
	cors.Validation(a)
}
