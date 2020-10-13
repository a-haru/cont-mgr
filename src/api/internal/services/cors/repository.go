package cors

type ValidationRepository interface {
	ContentIdMatchedClientUrl(id, url string) bool
}
